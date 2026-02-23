<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>💻 Editor | Offline Code Editor</title>

<!-- Bootstrap + Tailwind -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<!-- CodeMirror Core -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>

<!-- Modes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>

<!-- Autocomplete -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/javascript-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/css-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/html-hint.min.js"></script>

<!-- Editing helpers -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

<!-- Linting -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/lint/lint.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/lint/lint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/lint/javascript-lint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/lint/css-lint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/lint/html-lint.min.js"></script>

<!-- Linters -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jshint/2.13.6/jshint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/csslint/1.0.5/csslint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/htmlhint/0.16.3/htmlhint.min.js"></script>

<!-- JSZip for download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<style>
body { background-color: #004F59; }
body.dark { background-color: #0f172a; }
.CodeMirror { height: 100%; font-size: 14px; border-radius: 8px; }
#tabs button { padding: 6px 12px; border-radius: 6px; margin-right: 6px; }
#tabs button.active { background: #facc15; color: black; font-weight: bold; }
iframe { width: 100%; height: 100%; border: 1px solid #ccc; border-radius: 6px; }
</style>
</head>
<body class="min-h-screen flex text-white transition-colors duration-500">

<!-- Sidebar -->
<aside class="w-64 bg-white/10 dark:bg-gray-900/40 backdrop-blur-md p-4 flex flex-col gap-4">
<h2 class="text-xl font-bold mb-4">📂 File Menu</h2>
<button id="newFileBtn" class="btn btn-sm btn-warning w-full">📄 New File</button>
<button id="openFileBtn" class="btn btn-sm btn-secondary w-full">📂 Open File</button>
<button id="openFolderBtn" class="btn btn-sm btn-dark w-full">📁 Open Folder</button>
<button id="saveFileBtn" class="btn btn-sm btn-success w-full">💾 Save File</button>

<h3 class="mt-6 text-lg font-semibold">🗂 Explorer</h3>
<ul id="fileExplorer" class="space-y-2 text-sm text-left">
<li class="italic text-gray-300">No files yet</li>
</ul>
</aside>

<!-- Editor + Preview -->
<main class="flex-grow flex flex-col">
<!-- Top Bar -->
<nav class="flex justify-between items-center px-6 py-3 bg-white/10 dark:bg-gray-800/40 backdrop-blur-md">
<div class="text-xl font-bold">✍️ Code Hub</div>
<div class="flex items-center gap-4">
<a href="dashboard.php" class="btn btn-primary px-4 py-2">🏠 Dashboard</a>
<button id="runBtn" class="btn btn-warning px-4 py-2">▶️ Run</button>
<button id="downloadZipBtn" class="btn btn-info px-4 py-2">📦 Download ZIP</button>
<button id="themeToggle" class="px-3 py-2 rounded-lg border border-white bg-white/20 backdrop-blur-md shadow">🌙</button>
</div>
</nav>

<!-- Tabs -->
<div id="tabs" class="flex px-4 py-2 bg-white/10 dark:bg-gray-800/40 overflow-x-auto"></div>

<!-- Editor + Preview Layout -->
<section class="flex-grow p-4 flex gap-4">
  <!-- Editor on the left -->
  <div class="flex flex-col flex-[2]">
    <textarea id="editorArea" class="flex-grow"></textarea>
    <div id="status" class="text-xs text-gray-300 mt-2"></div>
  </div>

  <!-- Live Preview on the right -->
  <div class="flex flex-col flex-[1]">
    <h3 class="text-sm mb-2">Live Preview:</h3>
    <iframe id="livePreview" class="flex-grow border rounded-md"></iframe>
  </div>
</section>
</main>

<script>
const themeToggle = document.getElementById("themeToggle");
const body = document.body;
const tabsDiv = document.getElementById("tabs");
const status = document.getElementById("status");

// Dark mode persistence
if(localStorage.getItem("theme") === "dark") {
  body.classList.add("dark");
  themeToggle.textContent = "☀️";
}
themeToggle.addEventListener("click", () => {
  body.classList.toggle("dark");
  if(body.classList.contains("dark")) {
    localStorage.setItem("theme","dark");
    themeToggle.textContent = "☀️";
  } else {
    localStorage.setItem("theme","light");
    themeToggle.textContent = "🌙";
  }
});

// CodeMirror init
let editor = CodeMirror.fromTextArea(document.getElementById("editorArea"), {
  lineNumbers: true,
  mode: "htmlmixed",
  theme: "dracula",
  autoCloseTags: true,
  autoCloseBrackets: true,
  gutters: ["CodeMirror-lint-markers"],
  lint: true,
  extraKeys: { "Ctrl-Space": "autocomplete" }
});

// File Manager
let files = {};
let currentFile = null;
const fileExplorer = document.getElementById("fileExplorer");

function setEditorMode(filename) {
  let ext = filename.split('.').pop();
  let mode = "text/plain";
  if (ext === "html") mode = "htmlmixed";
  else if (ext === "css") mode = "css";
  else if (ext === "js") mode = "javascript";
  editor.setOption("mode", mode);
  editor.setOption("lint", true);
}

function refreshExplorer() {
  fileExplorer.innerHTML = "";
  for (let name in files) {
    let li = document.createElement("li");
    li.textContent = name;
    li.className = "cursor-pointer hover:underline";
    li.onclick = () => openFileInTab(name);
    fileExplorer.appendChild(li);
  }
  if(Object.keys(files).length === 0){
    fileExplorer.innerHTML = "<li class='italic text-gray-300'>No files yet</li>";
  }
}

function refreshTabs() {
  tabsDiv.innerHTML = "";
  for (let name in files) {
    let btn = document.createElement("button");
    btn.textContent = name;
    btn.className = (name === currentFile) ? "active" : "bg-gray-700";
    btn.onclick = () => openFileInTab(name);
    tabsDiv.appendChild(btn);
  }
}

function openFileInTab(name) {
  currentFile = name;
  editor.setValue(files[name]);
  setEditorMode(name);
  refreshTabs();
}

// File Buttons
document.getElementById("newFileBtn").onclick = () => {
  const name = prompt("Enter new file name (e.g. index.html):");
  if(name && !files[name]){
    files[name] = "";
    openFileInTab(name);
    refreshExplorer();
  }
};


 document.getElementById("saveFileBtn").onclick = () => {
      if(currentFile){
        const blob = new Blob([editor.getValue()], { type: "text/plain" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = currentFile;
        link.click();
        status.textContent = "💾 Saved to local drive!";
      } else {
        alert("⚠️ No file selected.");
      }
    };



document.getElementById("openFileBtn").onclick = () => {
  const input = document.createElement("input");
  input.type = "file";
  input.onchange = e => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = function(evt){
      files[file.name] = evt.target.result;
      openFileInTab(file.name);
      refreshExplorer();
    }
    reader.readAsText(file);
  }
  input.click();
};

document.getElementById("openFolderBtn").onclick = () => {
  const input = document.createElement("input");
  input.type = "file";
  input.webkitdirectory = true;
  input.multiple = true;
  input.onchange = e => {
    [...e.target.files].forEach(file => {
      const reader = new FileReader();
      reader.onload = function(evt){
        files[file.name] = evt.target.result;
        if(!currentFile) openFileInTab(file.name);
        refreshExplorer();
      }
      reader.readAsText(file);
    });
  };
  input.click();
};

// Run button and live preview
function updatePreview() {
  if (!currentFile) return;
  files[currentFile] = editor.getValue() || "";

  const previewFrame = document.getElementById("livePreview");
  if (!previewFrame) return;

  const previewDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;

  // Get HTML content from current file
  let htmlContent = files[currentFile];

  // Collect external CSS & JS from other files
  let externalCSS = "";
  let externalJS = "";

  for (let name in files) {
    if (name === currentFile) continue; // skip current HTML file
    const ext = name.split('.').pop().toLowerCase();
    if (ext === "css") externalCSS += files[name] + "\n";
    else if (ext === "js") externalJS += files[name] + "\n";
  }

  // Inject external CSS into <head> without touching internal CSS
  if (/<head>/i.test(htmlContent)) {
    htmlContent = htmlContent.replace(
      /<head>/i,
      `<head><style>${externalCSS}</style>`
    );
  } else {
    htmlContent = `<head><style>${externalCSS}</style></head>` + htmlContent;
  }

  // Inject external JS before </body> without touching internal JS
  if (/<\/body>/i.test(htmlContent)) {
    htmlContent = htmlContent.replace(
      /<\/body>/i,
      `<script>${externalJS}<\/script></body>`
    );
  } else {
    htmlContent += `<script>${externalJS}<\/script>`;
  }

  // Render in iframe
  previewDoc.open();
  previewDoc.write(htmlContent);
  previewDoc.close();

  status.textContent = "▶️ Live preview updated!";
}




//document.getElementById("runBtn").onclick = updatePreview;


// Download ZIP
document.getElementById("downloadZipBtn").onclick = async () => {
  if(Object.keys(files).length === 0){
    alert("⚠️ No files to download.");
    return;
  }
  const zip = new JSZip();
  for (let name in files) {
    zip.file(name, files[name]);
  }
  const blob = await zip.generateAsync({type:"blob"});
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "project.zip";
  link.click();
};

// Real-time live preview
editor.on("change", () => {
  updatePreview();
});
</script>

</body>
</html>
