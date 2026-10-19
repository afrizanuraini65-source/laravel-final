import re

filepath = 'c:/laragon/www/BISMILLAH-main/resources/views/kegiatan/index.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Default drive link that was used for Forum Rutin
default_drive_link = 'https://drive.google.com/drive/folders/1aj0xMdVb-TigPADwO_RNf6IlL2fBScND'
specific_genre_link = 'https://drive.google.com/drive/folders/1TppstzSEojbIA-dfLkQxsCbpbI3dERkK?usp=drive_link'

# We'll use regex to process each array block.
# An item block looks like:
#         [
#             'type' => 'Forum',
#             'title' => 'Forum Rutin Minggu 1',
#             ...
#             'docs_link' => '...',
#         ],

def process_item(match):
    block = match.group(0)
    
    # Extract title
    title_match = re.search(r"'title'\s*=>\s*'([^']+)'", block)
    title = title_match.group(1).lower() if title_match else ""
    
    # Determine the link
    if 'forum rutin' in title:
        new_link = "null"
    elif 'pemilihan duta genre' in title and 'pembekalan' not in title and 'technical' not in title and 'seleksi' not in title:
        new_link = f"'{specific_genre_link}'"
    else:
        new_link = f"'{default_drive_link}'"
        
    # Replace the existing docs_link
    new_block = re.sub(r"'docs_link'\s*=>\s*(?:'[^']*'|null)", f"'docs_link' => {new_link}", block)
    return new_block

# Find and replace all items array elements
new_content = re.sub(r'\[\s*\'type\'\s*=>\s*\'[^\']+\',[\s\S]*?\'docs_link\'\s*=>\s*(?:\'[^\']*\'|null),[\s\S]*?\]', process_item, content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Done")
