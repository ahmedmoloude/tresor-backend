import re

def remove_lock_unlock(file_path, output_path):
    with open(file_path, 'r') as file:
        content = file.read()

    # Remove lines containing LOCK TABLES and UNLOCK TABLES
    cleaned_content = re.sub(r'.*LOCK TABLES.*\n', '', content)
    cleaned_content = re.sub(r'.*UNLOCK TABLES.*\n', '', cleaned_content)



    with open(output_path, 'w') as output_file:
        output_file.write(cleaned_content)

    print(f"Cleaned SQL dump saved to: {output_path}")

# Provide the path to your SQL file and the output file
input_file = '/Users/mac/Desktop/tressor-new-project/tressor-backend/postgis_dump.sql'
output_file = '/Users/mac/Desktop/tressor-new-project/tressor-backend/postgis_dump_cleaned.sql'

remove_lock_unlock(input_file, output_file)
