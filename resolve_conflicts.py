import os
root = r'D:\LARAVEL\SM\Sistem-Informasi-Sekolah'
changed = []
for dirpath, dirnames, filenames in os.walk(root):
    if any(part in ('vendor', '.git', 'node_modules', 'storage') for part in dirpath.split(os.sep)):
        continue
    for fn in filenames:
        if fn.endswith(('.php', '.blade.php', '.css', '.js', '.md', '.txt')):
            path = os.path.join(dirpath, fn)
            with open(path, 'r', encoding='utf-8', errors='ignore') as f:
                lines = f.readlines()
            if any(line.startswith('<<<<<<< HEAD') for line in lines):
                out=[]
                i=0
                modified=False
                while i < len(lines):
                    if lines[i].startswith('<<<<<<< HEAD'):
                        modified=True
                        i += 1
                        while i < len(lines) and not lines[i].startswith('======='):
                            out.append(lines[i])
                            i += 1
                        if i < len(lines) and lines[i].startswith('======='):
                            i += 1
                        while i < len(lines) and not lines[i].startswith('>>>>>>>'):
                            i += 1
                        if i < len(lines) and lines[i].startswith('>>>>>>>'):
                            i += 1
                    else:
                        out.append(lines[i])
                        i += 1
                if modified:
                    with open(path, 'w', encoding='utf-8') as f:
                        f.writelines(out)
                    changed.append(path)
print('modified files:')
for p in changed:
    print(p)
