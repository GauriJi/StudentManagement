path = r"C:\xampp\htdocs\SchoolManagement\resources\views\partials\menu.blade.php"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

# keep 0 to 44 (which is indices 0-44, total 45 lines -> lines 1-45 in 1-based)
# keep 222 to end (which is indices 222 to end, so lines 223+ in 1-based)
new_lines = lines[:45] + lines[222:]

with open(path, "w", encoding="utf-8") as f:
    f.writelines(new_lines)

print("Removed lines 46 through 222 successfully.")
