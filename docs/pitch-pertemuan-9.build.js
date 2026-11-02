const path = require("path");
const fs = require("fs");
const pptxgen = require("pptxgenjs");
const { applyTheme } = require("C:/Users/imal_/.claude/skills/synced/4ff19ea0-0ff5-43f9-8dad-4d39f0144f6c_8220a119-079c-4bec-ab72-f456efccf741/pptx/scripts/apply_theme.js");

const REPO = "C:/Users/imal_/Desktop/python/laravel-framework/eager-loading";
const OUT = path.join(REPO, "docs", "pitch-pertemuan-9.pptx");
const SCREENSHOT = path.join(REPO, "docs", "debugbar-chat-session.jpg");

const THEME = {
  name: "PBKK Agentic AI",
  headFontFace: "Cambria",
  bodyFontFace: "Calibri",
  colors: {
    dk1: "14213D", // navy: teks utama, latar gelap
    lt1: "FFFFFF",
    dk2: "4A5568", // teks sekunder
    lt2: "EEF1F6", // latar kartu
    accent1: "A9530F", // oranye tua (di latar terang)
    accent2: "F2A057", // oranye terang (di latar gelap)
    accent3: "2C3B5C", // navy sedang (chip)
    accent4: "0D1730", // navy paling gelap (blok kode)
    accent5: "CBD3DF", // garis kartu
    accent6: "C9D3E3", // teks sekunder di latar gelap
    hlink: "2B5FB0",
    folHlink: "6B4FA0",
  },
};

const pres = new pptxgen();
pres.layout = "LAYOUT_WIDE"; // 13.333 x 7.5 in
pres.title = "Pitch Relasi Antar Model & Eager Loading";
pres.author = "Kelompok [__]";
pres.theme = { headFontFace: THEME.headFontFace, bodyFontFace: THEME.bodyFontFace };
const C = pres.SchemeColor;
const MONO = "Courier New";
const W = 13.333;
const MX = 0.6;
const CW = W - 2 * MX; // 12.133

/* ---------- Layouts ---------- */
pres.defineSlideMaster({
  title: "COVER",
  background: { color: C.text1 },
  objects: [
    { placeholder: { options: { name: "eyebrow", type: "body", x: MX, y: 0.7, w: 8.3, h: 0.4, fontSize: 14, bold: true, color: C.accent2, margin: 0 }, text: "" } },
    { placeholder: { options: { name: "title", type: "title", x: MX, y: 1.6, w: 8.3, h: 1.8, fontSize: 40, bold: true, color: C.background1, align: "left", valign: "top", margin: 0 }, text: "" } },
    { placeholder: { options: { name: "subtitle", type: "body", x: MX, y: 3.75, w: 8.0, h: 1.0, fontSize: 18, color: C.background2, valign: "top", margin: 0 }, text: "" } },
    { placeholder: { options: { name: "team", type: "body", x: MX, y: 5.9, w: 8.3, h: 0.9, fontSize: 16, color: C.accent6, valign: "bottom", margin: 0 }, text: "" } },
  ],
});

for (const [name, dark] of [["CONTENT_LIGHT", false], ["CONTENT_DARK", true]]) {
  pres.defineSlideMaster({
    title: name,
    background: { color: dark ? C.text1 : C.background1 },
    objects: [
      { placeholder: { options: { name: "eyebrow", type: "body", x: MX, y: 0.45, w: 9, h: 0.35, fontSize: 12, bold: true, color: dark ? C.accent2 : C.accent1, margin: 0 }, text: "" } },
      { placeholder: { options: { name: "title", type: "title", x: MX, y: 0.85, w: CW, h: 0.75, fontSize: 36, bold: true, color: dark ? C.background1 : C.text1, align: "left", valign: "top", margin: 0 }, text: "" } },
    ],
    slideNumber: { x: 12.33, y: 7.0, w: 0.4, h: 0.3, fontSize: 10, color: dark ? C.accent6 : C.text2, align: "right" },
  });
}

const eyebrow = (slide, text) => slide.addText(text, { placeholder: "eyebrow", charSpacing: 2 });

/* ---------- 1. Cover ---------- */
pres.addSection({ title: "Pembuka" });
{
  const s = pres.addSlide({ masterName: "COVER", sectionTitle: "Pembuka" });
  eyebrow(s, "PBKK · PERTEMUAN 9 · TUGAS KELOMPOK");
  s.addText("Agentic AI Platform: Relasi Antar Model & Eager Loading", { placeholder: "title" });
  s.addText("Sesi obrolan, isi pesan, dan log pemanggilan alat oleh agent AI, dimodelkan dengan Eloquent tanpa N+1 query.", { placeholder: "subtitle" });
  s.addText("Kelompok [__]\n[Nama Anggota 1] · [Nama Anggota 2] · [Nama Anggota 3]", { placeholder: "team" });

  // Motif: rantai tabel inti
  const chain = ["users", "chat_sessions", "chat_messages", "tool_execution_logs"];
  const nodeX = 9.55, nodeW = 3.18, nodeH = 0.6;
  chain.forEach((label, i) => {
    const y = 1.6 + i * 1.05;
    s.addText(label, {
      shape: pres.shapes.ROUNDED_RECTANGLE, rectRadius: 0.12, x: nodeX, y, w: nodeW, h: nodeH,
      fill: { color: i === chain.length - 1 ? C.accent2 : C.accent3 },
      color: i === chain.length - 1 ? C.text1 : C.background1,
      fontFace: MONO, fontSize: 14, bold: true, align: "center", valign: "middle", margin: 0,
      objectName: `Cover chain ${label}`,
    });
    if (i < chain.length - 1) {
      s.addShape(pres.shapes.DOWN_ARROW, {
        x: nodeX + nodeW / 2 - 0.13, y: y + nodeH + 0.08, w: 0.26, h: 0.29,
        fill: { color: C.accent6 }, line: { color: C.accent6, width: 0 }, objectName: `Cover chain arrow ${i + 1}`,
      });
    }
  });
  s.addText("Laravel 13 · Breeze · Pest", {
    x: nodeX, y: 6.3, w: nodeW, h: 0.4, fontFace: MONO, fontSize: 13, color: C.accent6, align: "right", margin: 0,
    isTextBox: true, objectName: "Cover stack",
  });
  s.addNotes("Pembukaan (±30 detik). Perkenalkan kelompok dan tujuan tugas: membangun pondasi basis data untuk platform Agentic AI, yaitu sesi chat, isi pesan, dan log pemanggilan alat, lengkap dengan relasi Eloquent yang efisien. Rantai di kanan adalah empat tabel inti yang akan dibahas.");
}

/* ---------- 2. ERD ---------- */
pres.addSection({ title: "Rancangan & Implementasi" });
{
  const s = pres.addSlide({ masterName: "CONTENT_LIGHT", sectionTitle: "Rancangan & Implementasi" });
  eyebrow(s, "RANCANGAN SKEMA & MIGRASI · 35%");
  s.addText("Empat tabel inti dalam satu rantai 1 : N", { placeholder: "title" });

  const cards = [
    ["User", "users", ["id PK", "name, email", "(Breeze)"]],
    ["ChatSession", "chat_sessions", ["id PK", "user_id FK", "title"]],
    ["ChatMessage", "chat_messages", ["id PK", "chat_session_id FK", "role, content"]],
    ["ToolExecutionLog", "tool_execution_logs", ["chat_message_id FK", "loggable_id/_type", "tool_name, status"]],
  ];
  const arrowW = 0.5, gap = 0.12;
  const cardW = (CW - 3 * (arrowW + 2 * gap)) / 4;
  const cardY = 2.1, cardH = 1.9;
  cards.forEach(([model, table, cols], i) => {
    const x = MX + i * (cardW + arrowW + 2 * gap);
    s.addShape(pres.shapes.ROUNDED_RECTANGLE, {
      x, y: cardY, w: cardW, h: cardH, rectRadius: 0.12,
      fill: { color: C.background2 }, line: { color: C.accent5, width: 1 }, objectName: `ERD card ${model}`,
    });
    s.addText([
      { text: model, options: { fontSize: 17, bold: true, color: C.text1, breakLine: true } },
      { text: table, options: { fontFace: MONO, fontSize: 13, bold: true, color: C.accent1, breakLine: true, paraSpaceBefore: 4 } },
      ...cols.map((c, j) => ({ text: c, options: { fontFace: MONO, fontSize: 13, color: C.text2, breakLine: j < cols.length - 1, paraSpaceBefore: j === 0 ? 10 : 2 } })),
    ], { x: x + 0.15, y: cardY + 0.15, w: cardW - 0.3, h: cardH - 0.3, valign: "top", margin: 0, isTextBox: true, objectName: `ERD text ${model}` });

    if (i < cards.length - 1) {
      const ax = x + cardW + gap;
      s.addText("1 : N", {
        x: ax - 0.1, y: cardY + cardH / 2 - 0.52, w: arrowW + 0.2, h: 0.3, fontSize: 12, bold: true, color: C.text2, align: "center", margin: 0,
        isTextBox: true, objectName: `ERD cardinality ${i + 1}`,
      });
      s.addShape(pres.shapes.RIGHT_ARROW, {
        x: ax, y: cardY + cardH / 2 - 0.16, w: arrowW, h: 0.32,
        fill: { color: C.accent1 }, line: { color: C.accent1, width: 0 }, objectName: `ERD arrow ${i + 1}`,
      });
    }
  });

  // Pita polymorphic
  const bandY = 4.35, bandH = 1.05;
  const lastCardCx = MX + 3 * (cardW + arrowW + 2 * gap) + cardW / 2;
  s.addShape(pres.shapes.UP_ARROW, {
    x: lastCardCx - 0.13, y: cardY + cardH + 0.06, w: 0.26, h: bandY - (cardY + cardH) - 0.12,
    fill: { color: C.text1 }, line: { color: C.text1, width: 0 }, objectName: "ERD morph arrow",
  });
  s.addShape(pres.shapes.ROUNDED_RECTANGLE, {
    x: MX, y: bandY, w: CW, h: bandH, rectRadius: 0.12, fill: { color: C.text1 }, line: { color: C.text1, width: 0 }, objectName: "Morph band",
  });
  s.addText([
    { text: "morphs('loggable')", options: { fontFace: MONO, fontSize: 16, bold: true, color: C.accent2, breakLine: true } },
    { text: "morphTo() ⇄ morphMany()", options: { fontSize: 13, color: C.accent6 } },
  ], { x: MX + 0.3, y: bandY, w: 3.4, h: bandH, valign: "middle", margin: 0, isTextBox: true, objectName: "Morph label" });
  ["SecurityScanner", "MalwareScanner", "LogAnalyzer"].forEach((agent, i) => {
    s.addText(agent, {
      shape: pres.shapes.ROUNDED_RECTANGLE, rectRadius: 0.1, x: 4.35 + i * 2.8, y: bandY + 0.25, w: 2.55, h: 0.55,
      fill: { color: C.accent3 }, color: C.background1, fontSize: 15, bold: true, align: "center", valign: "middle", margin: 0,
      objectName: `Agent chip ${agent}`,
    });
  });

  s.addText([
    { text: "Setiap FK: ", options: { bold: true, color: C.text1 } },
    { text: "foreignId()->constrained()->cascadeOnDelete()", options: { fontFace: MONO, color: C.accent1 } },
    { text: ". Hapus user, maka sesi, pesan, dan log ikut terhapus. Kolom morph tidak bisa diberi FK, jadi log milik agent dihapus oleh trait HasToolExecutionLogs.", options: { color: C.text2 } },
  ], { x: MX, y: 5.65, w: CW, h: 0.75, fontSize: 14, valign: "top", margin: 0, isTextBox: true, objectName: "Cascade note" });

  s.addNotes("Rancangan skema (±1 menit). Satu User punya banyak ChatSession, satu sesi punya banyak ChatMessage, satu pesan punya banyak ToolExecutionLog. Semua foreign key memakai cascadeOnDelete dan sudah diuji: menghapus user ikut menghapus sesi, pesan, dan log. Kolom loggable_id dan loggable_type dibuat oleh morphs('loggable') sehingga satu tabel log bisa dipakai tiga tipe agent. Karena kolom morph tidak bisa diberi foreign key, cascade saat agent dihapus dilakukan oleh trait HasToolExecutionLogs lewat event deleting. ERD lengkap ada di README repositori.");
}

/* ---------- 3. Relasi model ---------- */
{
  const s = pres.addSlide({ masterName: "CONTENT_LIGHT", sectionTitle: "Rancangan & Implementasi" });
  eyebrow(s, "IMPLEMENTASI MODEL & ORM · 25%");
  s.addText("Relasi mengikuti konvensi nama Eloquent", { placeholder: "title" });

  const head = (t) => ({ text: t, options: { bold: true, color: C.background1, fill: { color: C.text1 } } });
  const model = (t) => ({ text: t, options: { bold: true, color: C.text1 } });
  const code = (t) => ({ text: t, options: { fontFace: MONO, fontSize: 13, color: C.text1 } });
  const rows = [
    [head("Model"), head("Method"), head("Relasi")],
    [model("User"), code("chatSessions()"), code("hasMany(ChatSession)")],
    [model("ChatSession"), code("user()\nmessages()"), code("belongsTo(User)\nhasMany(ChatMessage)")],
    [model("ChatMessage"), code("chatSession()\ntoolExecutionLogs()"), code("belongsTo(ChatSession)\nhasMany(ToolExecutionLog)")],
    [model("ToolExecutionLog"), code("chatMessage()\nloggable()"), code("belongsTo(ChatMessage)\nmorphTo()")],
    [model("SecurityScanner\nMalwareScanner\nLogAnalyzer"), code("logs()"), code("morphMany(ToolExecutionLog)")],
  ];
  s.addTable(rows, {
    x: MX, y: 1.95, w: 8.3, colW: [2.0, 2.8, 3.5], fontSize: 14, color: C.text1, valign: "middle",
    border: { type: "solid", pt: 0.75, color: THEME.colors.accent5 }, fill: { color: C.background1 }, margin: [0.06, 0.12, 0.06, 0.12],
    objectName: "Relations table",
  });

  // Stat column
  const px = 9.3, pw = W - MX - px;
  s.addShape(pres.shapes.ROUNDED_RECTANGLE, {
    x: px, y: 1.95, w: pw, h: 4.85, rectRadius: 0.12, fill: { color: C.background2 }, line: { color: C.accent5, width: 1 }, objectName: "Seed panel",
  });
  s.addText("Data dummy dari factory", {
    x: px + 0.3, y: 2.15, w: pw - 0.6, h: 0.4, fontSize: 16, bold: true, color: C.text1, margin: 0, isTextBox: true, objectName: "Seed panel title",
  });
  [["5", "user"], ["15", "sesi obrolan"], ["60", "pesan"], ["120", "log alat"]].forEach(([n, label], i) => {
    s.addText([
      { text: n, options: { fontFace: THEME.headFontFace, fontSize: 36, bold: true, color: C.accent1 } },
      { text: `  ${label}`, options: { fontSize: 16, color: C.text2 } },
    ], { x: px + 0.3, y: 2.7 + i * 0.85, w: pw - 0.6, h: 0.75, valign: "middle", margin: 0, isTextBox: true, objectName: `Seed stat ${label}` });
  });
  s.addText("Satu DatabaseSeeder dengan factory bertingkat has().", {
    x: px + 0.3, y: 6.15, w: pw - 0.6, h: 0.5, fontSize: 12, color: C.text2, valign: "top", margin: 0, isTextBox: true, objectName: "Seed panel caption",
  });

  s.addText("Relasi yang mengembalikan banyak data bernama jamak, yang mengembalikan satu data bernama tunggal; setiap method punya return type.", {
    x: MX, y: 5.45, w: 8.3, h: 0.65, fontSize: 14, color: C.text2, valign: "top", margin: 0, isTextBox: true, objectName: "Naming note",
  });

  s.addNotes("Model dan ORM (±1 menit). Relasi yang mengembalikan banyak data diberi nama jamak: chatSessions, messages, toolExecutionLogs, logs. Relasi yang mengembalikan satu data diberi nama tunggal: user, chatSession, chatMessage. Setiap method memakai return type HasMany, BelongsTo, MorphTo, atau MorphMany. Seeder memakai factory bertingkat dengan has() sehingga satu perintah migrate:fresh --seed langsung mengisi seluruh rantai: 5 user, 15 sesi, 60 pesan, 120 log.");
}

/* ---------- 4. Eager loading ---------- */
{
  const s = pres.addSlide({ masterName: "CONTENT_DARK", sectionTitle: "Rancangan & Implementasi" });
  eyebrow(s, "OPTIMASI EAGER LOADING · 20%");
  s.addText("Riwayat obrolan cukup 9 query", { placeholder: "title" });

  const leftW = 5.0;
  s.addText([
    { text: "$chatSession->load([", options: { breakLine: true } },
    { text: "  'messages' => fn ($q) => $q->oldest(),", options: { breakLine: true } },
    { text: "  'messages.toolExecutionLogs.loggable',", options: { breakLine: true } },
    { text: "]);" },
  ], {
    shape: pres.shapes.ROUNDED_RECTANGLE, rectRadius: 0.12, x: MX, y: 1.95, w: leftW, h: 1.45,
    fill: { color: C.accent4 }, line: { color: C.accent3, width: 1 },
    fontFace: MONO, fontSize: 13, color: C.background1, valign: "middle", margin: 14, objectName: "Eager load code",
  });

  s.addText([
    { text: "9", options: { fontFace: THEME.headFontFace, fontSize: 66, bold: true, color: C.accent2 } },
    { text: "  query untuk satu halaman", options: { fontSize: 16, color: C.accent6, breakLine: true } },
    { text: "konstan, berapa pun jumlah pesan dan log", options: { fontSize: 14, color: C.accent6 } },
  ], { x: MX, y: 3.6, w: leftW, h: 1.35, valign: "middle", margin: 0, isTextBox: true, objectName: "Query count stat" });

  s.addText([
    { text: "Satu query WHERE … IN (…) per tabel, ditambah satu per tipe agent.", options: { bullet: true, breakLine: true } },
    { text: "preventLazyLoading() aktif: lazy load langsung error.", options: { bullet: true, breakLine: true } },
    { text: "Test Pest: sesi 1 pesan dan 10 pesan menjalankan jumlah query yang sama.", options: { bullet: true } },
  ], { x: MX, y: 5.1, w: leftW, h: 1.6, fontSize: 14, color: C.background2, paraSpaceAfter: 6, valign: "top", margin: 0, isTextBox: true, objectName: "Eager bullets" });

  const imgX = MX + leftW + 0.45, imgW = W - MX - imgX, imgH = imgW / (1523 / 784);
  s.addImage({ path: SCREENSHOT, x: imgX, y: 1.95, w: imgW, h: imgH, altText: "Laravel Debugbar pada /chat-sessions/1: 9 statement, setiap relasi dimuat dengan satu query WHERE IN", objectName: "Debugbar screenshot" });
  s.addText("Laravel Debugbar · tab Queries · /chat-sessions/1", {
    x: imgX, y: 1.95 + imgH + 0.1, w: imgW, h: 0.3, fontSize: 11, color: C.accent6, margin: 0, isTextBox: true, objectName: "Screenshot caption",
  });

  s.addNotes("Optimasi eager loading (±1,5 menit, bagian terpenting). Di controller, satu pemanggilan load() dengan notasi titik memuat pesan, log alat, dan agent sekaligus. Screenshot Laravel Debugbar menunjukkan 9 statement: 3 dari session, user login, dan route binding, lalu 1 query pesan, 1 query log, 3 query agent (satu per tipe karena polymorphic), dan 1 update session. Tidak ada query yang berulang per baris. Sebagai pengaman, Model::preventLazyLoading() aktif di luar production, dan ada test Pest yang membandingkan jumlah query untuk sesi kecil dan besar.");
}

/* ---------- 5. Rubrik ---------- */
pres.addSection({ title: "Penutup" });
{
  const s = pres.addSlide({ masterName: "CONTENT_LIGHT", sectionTitle: "Penutup" });
  eyebrow(s, "RINGKASAN");
  s.addText("Setiap kriteria rubrik punya bukti", { placeholder: "title" });

  const items = [
    ["35%", "Skema & Migrasi", "4 tabel inti, FK cascadeOnDelete, ERD di README"],
    ["25%", "Model & ORM", "hasMany / belongsTo dengan nama jamak dan tunggal"],
    ["20%", "Eager Loading", "with() bertingkat, 9 query di Debugbar, test N+1"],
    ["10%", "Challenge A+", "morphs('loggable') untuk 3 tipe agent"],
    ["10%", "Kerapian PSR-12", "Laravel Pint bersih, 33 test Pest lulus"],
  ];
  const gap = 0.3, cw = (CW - 4 * gap) / 5, cy = 2.25, ch = 2.5;
  items.forEach(([weight, title, proof], i) => {
    const x = MX + i * (cw + gap);
    s.addShape(pres.shapes.ROUNDED_RECTANGLE, {
      x, y: cy, w: cw, h: ch, rectRadius: 0.12, fill: { color: C.background2 }, line: { color: C.accent5, width: 1 }, objectName: `Rubric card ${title}`,
    });
    s.addText([
      { text: weight, options: { fontFace: THEME.headFontFace, fontSize: 36, bold: true, color: C.accent1, breakLine: true } },
      { text: title, options: { fontSize: 16, bold: true, color: C.text1, breakLine: true, paraSpaceBefore: 6 } },
      { text: proof, options: { fontSize: 15, color: C.text2, paraSpaceBefore: 8 } },
    ], { x: x + 0.2, y: cy + 0.2, w: cw - 0.4, h: ch - 0.4, valign: "top", margin: 0, isTextBox: true, objectName: `Rubric text ${title}` });
  });

  s.addShape(pres.shapes.ROUNDED_RECTANGLE, {
    x: MX, y: 5.15, w: CW, h: 1.15, rectRadius: 0.12, fill: { color: C.text1 }, line: { color: C.text1, width: 0 }, objectName: "Closing band",
  });
  s.addText([
    { text: "Repositori: ", options: { bold: true, color: C.background1 } },
    { text: "[github.com/__/__]", options: { fontFace: MONO, color: C.accent2 } },
    { text: "   ·   Akun demo: ", options: { bold: true, color: C.background1 } },
    { text: "test@example.com", options: { fontFace: MONO, color: C.accent2, breakLine: true } },
    { text: "Tambahan: login Breeze + policy, sehingga user hanya bisa membuka sesi obrolannya sendiri.", options: { color: C.accent6 } },
  ], { x: MX + 0.3, y: 5.15, w: CW - 0.6, h: 1.15, fontSize: 15, valign: "middle", margin: 0, isTextBox: true, objectName: "Closing text" });

  s.addNotes("Penutup (±1 menit). Petakan hasil ke rubrik: skema dan migrasi dengan cascade, relasi sesuai konvensi, eager loading bertingkat yang terbukti di Debugbar dan test, challenge polymorphic logging, dan kode yang dirapikan dengan Pint. Tambahan: halaman riwayat obrolan dilindungi login Breeze dan policy, jadi user hanya bisa membuka sesinya sendiri. Tutup dengan tautan repositori lalu buka sesi tanya jawab.");
}

(async () => {
  fs.mkdirSync(path.dirname(OUT), { recursive: true });
  await pres.writeFile({ fileName: OUT });
  await applyTheme(OUT, THEME);
  console.log("wrote", OUT);
})();
