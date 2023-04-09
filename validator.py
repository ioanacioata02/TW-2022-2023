import os
import sys
import cssutils
import tidylib

def validate_html(html_file_path):
    with open(html_file_path, 'r') as f:
        html = f.read()

    options = {
        'output-xhtml': 1,
        'show-errors': 1,
        'force-output': 1,
        'wrap': 0
    }

    result = tidylib.tidy_document(html, options=options)
    errors = result[1]

    if errors:
        print(f'Errors found in {html_file_path}:')
        for error in errors:
            print(f'Line {error["line"]}, column {error["column"]}: {error["message"]}')
        return False
    else:
        print(f'{html_file_path} is valid HTML.')
        return True


def validate_css(css_file_path):
    with open(css_file_path, 'r') as f:
        css = f.read()

    try:
        cssutils.parseString(css)
        print(f'{css_file_path} is valid CSS.')
        return True
    except Exception as e:
        print(f'Errors found in {css_file_path}: {e}')
        return False


if __name__ == '__main__':
    html_dir = '/path/to/html/directory'
    css_dir = '/path/to/css/directory'

    html_files = [os.path.join(html_dir, f) for f in os.listdir(html_dir) if f.endswith('.html')]
    css_files = [os.path.join(css_dir, f) for f in os.listdir(css_dir) if f.endswith('.css')]

    for html_file in html_files:
        validate_html(html_file)

    for css_file in css_files:
        validate_css(css_file)
