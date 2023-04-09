import os
import cssutils
from html5validator import HTML5Validator

# Set the directories to validate
html_directory = "F:\Reps\clone3\TW-2022-2023\Front\source"
css_directory = "F:\Reps\clone3\TW-2022-2023\Front\styles"

# Get a list of all HTML and CSS files in their respective directories
html_files = [filename for filename in os.listdir(html_directory) if filename.endswith('.html')]
css_files = [filename for filename in os.listdir(css_directory) if filename.endswith('.css')]

# Validate each HTML file
with open('log.txt', 'w') as log_file:
    for html_file in html_files:
            validator = html5validator()
            validator.validate_file(os.path.join(html_directory, html_file))
            log_file.write(f"{html_file} is valid HTML.\n")


    # Validate each CSS file
    for css_file in css_files:
        try:
            with open(os.path.join(css_directory, css_file), 'rb') as f:
                cssutils.parseString(f.read())
            log_file.write(f"{css_file} is valid CSS.\n")
        except Exception as error:
            log_file.write(f"{css_file} is not valid CSS: {error}\n")

print("Validation complete. Results written to log.txt")
