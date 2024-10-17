import re
import sys

def convert_mysql_to_postgis(input_file, output_file):
    try:
        with open(input_file, 'r') as infile, open(output_file, 'w') as outfile:
            content = infile.read()
            
            # Remove MySQL-specific syntax
            content = re.sub(r'ENGINE=\w+', '', content)
            content = re.sub(r'\s*DEFAULT CHARSET=\w+(\s*COLLATE=\w+)?', '', content)
            content = content.replace('`', '"')
            
            # Convert data types
            content = re.sub(r'int\(\d+\)', 'integer', content)
            content = re.sub(r'bigint\(\d+\)', 'bigint', content)
            content = re.sub(r'mediumint\(\d+\)', 'integer', content)
            content = content.replace('UNSIGNED', '')
            content = content.replace('longtext', 'TEXT')
            
            # Convert ENUM types to CHECK constraints
            def enum_to_check(match):
                column_name = match.group(1)
                enum_values = match.group(2)
                return f'{column_name} TEXT CHECK ({column_name} IN ({enum_values}))'
            content = re.sub(r'"(\w+)"\s+enum\((.*?)\)', enum_to_check, content)
            
            # Convert AUTO_INCREMENT to SERIAL
            content = content.replace('AUTO_INCREMENT', 'SERIAL')
            
            # Remove or adjust MySQL-specific column attributes
            content = re.sub(r'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 'DEFAULT CURRENT_TIMESTAMP', content)
            
            # Convert geometry types
            content = content.replace('GEOMETRY', 'geometry')
            content = re.sub(r'(POINT|LINESTRING|POLYGON)', r'geometry(\1)', content)
            
            # Update spatial functions
            content = content.replace('GeomFromText', 'ST_GeomFromText')
            content = re.sub(r'([XY])\(', r'ST_\1(', content)
            
            # Remove sql_mode and time_zone settings
            content = re.sub(r'SET\s+(?:sql_mode|time_zone).*?;\n', '', content)
            
            # Convert KEY to INDEX
            content = re.sub(r'ADD\s+(UNIQUE\s+)?KEY', r'ADD \1INDEX', content)
            
            # Remove ALGORITHM, DEFINER, and SQL SECURITY DEFINER from view definitions
            content = re.sub(r'CREATE\s+ALGORITHM.*?DEFINER.*?SQL\s+SECURITY\s+DEFINER\s+VIEW', 'CREATE VIEW', content)
            
            # Fix INSERT statements
            def fix_insert(match):
                table = match.group(1)
                columns = match.group(2)
                values = match.group(3).replace('\n', ' ').split('),(')
                fixed_values = []
                for value in values:
                    value = value.strip('(').strip(')')
                    fixed_values.append(f"({value})")
                return f"INSERT INTO {table} ({columns}) VALUES\n" + ",\n".join(fixed_values) + ";"
            content = re.sub(r"INSERT INTO ([^\(]+) \((.*?)\) VALUES\s*\((.*?)\);", fix_insert, content, flags=re.DOTALL)
            
            # Remove SERIAL=X statements
            content = re.sub(r'SERIAL=\d+;', '', content)
            
            # Convert MODIFY statements to ALTER COLUMN
            content = re.sub(r'MODIFY\s+"(\w+)"\s+(\w+)(.*)SERIAL(.*?),?', r'ALTER COLUMN "\1" TYPE \2, ALTER COLUMN "\1" SET NOT NULL, ALTER COLUMN "\1" ADD GENERATED ALWAYS AS IDENTITY;', content)
            
            # Fix UNIQUE INDEX syntax
            content = re.sub(r'ADD UNIQUE INDEX "(\w+)" \((.*?)\);', r'ADD CONSTRAINT "\1" UNIQUE (\2);', content)
            
            outfile.write(content)

        print("Conversion complete. Please review the output file manually for any remaining issues.")
    except Exception as e:
        print(f"An error occurred: {str(e)}")
        print(f"Error on line {sys.exc_info()[-1].tb_lineno}")

# Usage
if __name__ == "__main__":
    convert_mysql_to_postgis('/Users/mac/Desktop/dump.sql', 'postgis_dump.sql')