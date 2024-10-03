import re
import sys

def convert_mysql_to_postgresql(input_file, output_file):
    with open(input_file, 'r') as infile, open(output_file, 'w') as outfile:
        in_create_table = False
        table_name = ""
        for line in infile:
            # Remove MySQL-specific SET statements
            if line.strip().startswith('SET '):
                continue

            # Convert CREATE TABLE syntax
            if line.strip().startswith('CREATE TABLE'):
                in_create_table = True
                table_name = re.search(r'`(\w+)`', line).group(1)
                line = f'CREATE TABLE IF NOT EXISTS "{table_name}" (\n'

            # Convert column definitions
            if in_create_table:
                line = re.sub(r'`(\w+)`', r'"\1"', line)  # Replace backticks with double quotes
                line = re.sub(r'int\(\d+\)', 'integer', line)  # Convert int(11) to integer
                line = re.sub(r'bigint\(\d+\)', 'bigint', line)  # Convert bigint(20) to bigint
                line = line.replace(' unsigned', '')  # Remove unsigned
                line = line.replace('AUTO_INCREMENT', 'SERIAL')  # Replace AUTO_INCREMENT with SERIAL

            # End of CREATE TABLE
            if line.strip() == ');':
                in_create_table = False

            # Convert INSERT statements
            if line.strip().startswith('INSERT INTO'):
                line = re.sub(r'`(\w+)`', r'"\1"', line)  # Replace backticks with double quotes

            # Convert ALTER TABLE statements
            if line.strip().startswith('ALTER TABLE'):
                line = re.sub(r'`(\w+)`', r'"\1"', line)  # Replace backticks with double quotes
                table_name = re.search(r'ALTER TABLE "(\w+)"', line).group(1)
                
                # Handle MODIFY statements
                modify_match = re.search(r'MODIFY "(\w+)" (\w+)( NOT NULL)? SERIAL, SERIAL=(\d+);', line)
                if modify_match:
                    column = modify_match.group(1)
                    data_type = modify_match.group(2)
                    not_null = modify_match.group(3) or ''
                    start_value = modify_match.group(4)
                    line = f'ALTER TABLE "{table_name}" ALTER COLUMN "{column}" TYPE {data_type};\n'
                    line += f'ALTER TABLE "{table_name}" ALTER COLUMN "{column}" {not_null};\n'
                    line += f'ALTER TABLE "{table_name}" ALTER COLUMN "{column}" SET DEFAULT nextval(\'{table_name}_{column}_seq\');\n'
                    line += f'CREATE SEQUENCE IF NOT EXISTS {table_name}_{column}_seq;\n'
                    line += f'SELECT setval(\'{table_name}_{column}_seq\', {start_value});\n'
                else:
                    line = line.replace('MODIFY COLUMN', 'ALTER COLUMN')
                    line = re.sub(r'int\(\d+\)', 'integer', line)  # Convert int(11) to integer
                    line = re.sub(r'bigint\(\d+\)', 'bigint', line)  # Convert bigint(20) to bigint

            # Remove ENGINE=InnoDB
            line = re.sub(r'ENGINE=\w+', '', line)

            # Write the converted line
            outfile.write(line)

    print(f"Conversion complete. Output file: {output_file}")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python script.py input_file.sql output_file.sql")
        sys.exit(1)
    
    input_file = sys.argv[1]
    output_file = sys.argv[2]
    convert_mysql_to_postgresql(input_file, output_file)