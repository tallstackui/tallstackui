#!/usr/bin/env python3
"""
Format and align markdown tables in .ai/ documentation files.

Handles escaped pipes (\\|) in table cells (e.g., string\\|null union types)
without splitting them into separate columns.

Usage:
    python3 scripts/format-markdown-tables.py [directory]

    directory  Path to process (default: .ai/)
"""

import os
import sys


def repair_and_format_table(lines):
    rows = []
    for line in lines:
        raw = line.strip()
        if raw.startswith('|'):
            raw = raw[1:]
        if raw.endswith('|'):
            raw = raw[:-1]

        cells = raw.split('|')

        merged = []
        i = 0
        while i < len(cells):
            cell = cells[i].strip()
            while cell.endswith('\\') and i + 1 < len(cells):
                i += 1
                cell = cell + '|' + cells[i].strip()
            merged.append(cell)
            i += 1

        rows.append(merged)

    if len(rows) < 2:
        return lines

    max_cols = max(len(r) for r in rows)
    for r in rows:
        while len(r) < max_cols:
            r.append('')

    trim_count = 0
    for col_idx in range(max_cols - 1, -1, -1):
        all_empty = True
        for row_idx, row in enumerate(rows):
            cell = row[col_idx].strip()
            if row_idx == 1:
                if cell and cell != '-' * len(cell):
                    all_empty = False
                    break
            else:
                if cell:
                    all_empty = False
                    break
        if all_empty:
            trim_count += 1
        else:
            break

    if trim_count > 0:
        for i in range(len(rows)):
            rows[i] = rows[i][:max_cols - trim_count]

    num_cols = len(rows[0])

    col_widths = [0] * num_cols
    for i, row in enumerate(rows):
        if i == 1:
            continue
        for j, cell in enumerate(row):
            if j < num_cols:
                col_widths[j] = max(col_widths[j], len(cell))

    col_widths = [max(w, 3) for w in col_widths]

    result = []
    for i, row in enumerate(rows):
        if i == 1:
            parts = ['-' * (w + 2) for w in col_widths]
            result.append('|' + '|'.join(parts) + '|')
        else:
            parts = [
                row[j].ljust(col_widths[j]) if j < len(row) else ' ' * col_widths[j]
                for j in range(num_cols)
            ]
            result.append('| ' + ' | '.join(parts) + ' |')

    return result


def process_file(filepath):
    with open(filepath, 'r') as f:
        lines = f.readlines()

    result = []
    table_lines = []
    in_table = False
    in_code_block = False

    for line in lines:
        stripped = line.rstrip('\n')
        trimmed = stripped.strip()

        # Track fenced code blocks (```/~~~). Lines inside a code block must
        # never be reformatted as a table even if they happen to start with `|`.
        if trimmed.startswith('```') or trimmed.startswith('~~~'):
            if in_table:
                formatted = repair_and_format_table(table_lines)
                for fl in formatted:
                    result.append(fl + '\n')
                in_table = False
                table_lines = []
            in_code_block = not in_code_block
            result.append(line)
            continue

        is_table_line = (
            not in_code_block
            and trimmed.startswith('|')
            and '|' in trimmed[1:]
        )

        if is_table_line:
            if not in_table:
                in_table = True
                table_lines = []
            table_lines.append(stripped)
        else:
            if in_table:
                formatted = repair_and_format_table(table_lines)
                for fl in formatted:
                    result.append(fl + '\n')
                in_table = False
                table_lines = []
            result.append(line)

    if in_table:
        formatted = repair_and_format_table(table_lines)
        for fl in formatted:
            result.append(fl + '\n')

    with open(filepath, 'w') as f:
        f.writelines(result)


def main():
    target_dir = sys.argv[1] if len(sys.argv) > 1 else '.ai'

    if not os.path.isabs(target_dir):
        target_dir = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), target_dir)

    if not os.path.isdir(target_dir):
        print(f'Error: directory not found: {target_dir}')
        sys.exit(1)

    count = 0
    for root, dirs, files in os.walk(target_dir):
        for fname in sorted(files):
            if fname.endswith('.md'):
                fpath = os.path.join(root, fname)
                process_file(fpath)
                count += 1
                print(f'Formatted: {os.path.relpath(fpath, target_dir)}')

    print(f'\nDone. Formatted {count} files.')


if __name__ == '__main__':
    main()
