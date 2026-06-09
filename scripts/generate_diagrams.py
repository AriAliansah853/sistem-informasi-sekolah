from PIL import Image, ImageDraw, ImageFont
import os

OUTPUT_DIR = os.path.join(os.path.dirname(__file__), '..', 'docs')
if not os.path.exists(OUTPUT_DIR):
    os.makedirs(OUTPUT_DIR, exist_ok=True)

font = ImageFont.load_default()
font_bold = ImageFont.load_default()

# Flowchart PNG
flow = Image.new('RGB', (1000, 700), 'white')
d = ImageDraw.Draw(flow)

boxes = [
    ((360, 20, 640, 80), 'Mulai', 'box'),
    ((300, 120, 700, 190), 'Landing Page Publik', 'box'),
    ((300, 230, 700, 300), 'Login / Register', 'box'),
    ((330, 340, 670, 410), 'Login Berhasil?', 'decision'),
    ((90, 470, 270, 530), 'Admin\nDashboard & CRUD', 'box'),
    ((310, 470, 490, 530), 'Guru\nMateri, Tugas, Tryout', 'box'),
    ((530, 470, 710, 530), 'Siswa\nMateri, Tugas, Hasil', 'box'),
    ((750, 470, 930, 530), 'Orang Tua\nNotifikasi & Tugas', 'box'),
    ((820, 330, 940, 390), 'Kembali', 'box'),
]

for coords, text, kind in boxes:
    fill = '#f3f7ff' if kind == 'box' else '#fff4cc'
    d.rounded_rectangle(coords, radius=12, fill=fill, outline='#3366cc' if kind == 'box' else '#dd9900', width=2)
    x0, y0, x1, y1 = coords
    lines = text.split('\n')
    for i, line in enumerate(lines):
        bbox = d.textbbox((0, 0), line, font=font)
        w = bbox[2] - bbox[0]
        h = bbox[3] - bbox[1]
        d.text((x0 + (x1 - x0 - w) / 2, y0 + 15 + i * 16), line, fill='#222', font=font)

arrows = [
    ((500, 80), (500, 120)),
    ((500, 190), (500, 230)),
    ((500, 300), (500, 340)),
    ((410, 410), (210, 470)),
    ((500, 410), (500, 470)),
    ((590, 410), (670, 470)),
    ((700, 410), (750, 470)),
    ((330, 375), (250, 375)),
    ((700, 375), (780, 375)),
    ((250, 375), (820, 375)),
    ((820, 390), (820, 430)),
]

for x1, y1, x2, y2 in ((a[0][0], a[0][1], a[1][0], a[1][1]) for a in arrows):
    d.line((x1, y1, x2, y2), fill='#444', width=2)
    # simple arrowhead
    d.polygon([(x2, y2), (x2-8, y2-5), (x2-8, y2+5)], fill='#444')

labels = [
    ((290, 365), 'Tidak'),
    ((760, 365), 'Ya'),
]
for pos, text in labels:
    d.text(pos, text, fill='#222', font=font)
flow.save(os.path.join(OUTPUT_DIR, 'flowchart.png'))

# ERD PNG
erd = Image.new('RGB', (1200, 900), 'white')
d = ImageDraw.Draw(erd)

entities = [
    ((40, 20, 320, 140), 'users', ['id (PK)', 'name', 'email', 'password']),
    ((340, 20, 600, 140), 'guru', ['id (PK)', 'nip', 'nama', 'mapel_id (FK)']),
    ((680, 20, 940, 140), 'mapel', ['id (PK)', 'nama_mapel', 'jurusan_id (FK)']),
    ((340, 160, 600, 280), 'kelas', ['id (PK)', 'nama_kelas', 'guru_id (FK)']),
    ((680, 160, 940, 280), 'siswa', ['id (PK)', 'nis', 'nama', 'kelas_id (FK)']),
    ((40, 260, 300, 360), 'orangtua', ['id (PK)', 'user_id (FK)', 'no_telp']),
    ((340, 260, 600, 360), 'orangtua_siswas', ['id (PK)', 'orangtua_id (FK)', 'siswa_id (FK)']),
    ((680, 280, 940, 380), 'absensi', ['id (PK)', 'siswa_id (FK)', 'tanggal', 'status']),
    ((40, 380, 300, 500), 'materi', ['id (PK)', 'judul', 'guru_id (FK)', 'kelas_id (FK)']),
    ((340, 420, 600, 540), 'tugas', ['id (PK)', 'judul', 'guru_id (FK)', 'kelas_id (FK)']),
    ((680, 420, 940, 540), 'penilaian_siswas', ['id (PK)', 'siswa_id (FK)', 'guru_id (FK)', 'mapel_id (FK)']),
    ((40, 540, 300, 680), 'bank_soals', ['id (PK)', 'guru_id (FK)', 'mapel_id (FK)', 'judul']),
    ((340, 580, 600, 720), 'try_outs', ['id (PK)', 'guru_id (FK)', 'mapel_id (FK)', 'kelas_id (FK)']),
    ((680, 560, 940, 680), 'try_out_soals', ['id (PK)', 'try_out_id (FK)', 'bank_soal_id (FK)']),
    ((940, 560, 1200, 680), 'jawaban_siswas', ['id (PK)', 'siswa_id (FK)', 'try_out_id (FK)', 'try_out_soal_id (FK)']),
    ((940, 40, 1200, 140), 'ppdb_years', ['id (PK)', 'year', 'source_url', 'summary']),
]

for coords, title, fields in entities:
    x0, y0, x1, y1 = coords
    d.rectangle(coords, fill='#ffffff', outline='#444', width=2)
    d.rectangle((x0, y0, x1, y0+30), fill='#e8f0ff', outline=None)
    d.text((x0+10, y0+20), title, fill='#1c3b6b', font=font_bold)
    for i, field in enumerate(fields):
        d.text((x0+10, y0+45 + i*18), field, fill='#222', font=font)

lines = [
    ((320, 60), (340, 60)),
    ((600, 60), (680, 60)),
    ((460, 100), (460, 160)),
    ((460, 220), (460, 260)),
    ((420, 380), (340, 380)),
    ((560, 380), (680, 380)),
    ((140, 310), (140, 260)),
    ((580, 310), (580, 260)),
    ((620, 360), (720, 360)),
    ((860, 360), (860, 380)),
    ((550, 450), (550, 480)),
    ((350, 500), (350, 540)),
    ((620, 540), (620, 580)),
    ((940, 600), (940, 640)),
]

for (x1, y1), (x2, y2) in lines:
    d.line((x1, y1, x2, y2), fill='#444', width=2)
    if x1 != x2 or y1 != y2:
        if x1 < x2:
            d.polygon([(x2, y2), (x2-8, y2-5), (x2-8, y2+5)], fill='#444')
        elif x1 > x2:
            d.polygon([(x2, y2), (x2+8, y2-5), (x2+8, y2+5)], fill='#444')
        elif y1 < y2:
            d.polygon([(x2, y2), (x2-5, y2-8), (x2+5, y2-8)], fill='#444')
        else:
            d.polygon([(x2, y2), (x2-5, y2+8), (x2+5, y2+8)], fill='#444')

erd.save(os.path.join(OUTPUT_DIR, 'erd.png'))

print('Generated docs/flowchart.png and docs/erd.png')
