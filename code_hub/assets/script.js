function saveFile() {
    let html = "<html><head><style>" + "" + "</style></head><body>" + "" + "</body></html>";
    let css = "";
    let js = "";
    let filename = "index.html";

    fetch("api/save_file.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ filename, html, css, js })
    })
    .then(res => res.json())
    .then(data => alert(data.status));
}
