const pdfUrl = document.getElementById("linkdolivro").innerHTML; // Caminho do PDF
let pdfDoc = null;

pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
    pdfDoc = pdf;
    document.getElementById("page-number").max = pdf.numPages; // Define o máximo de páginas disponíveis
    renderPage(); // Renderiza a primeira página
}).catch(error => {
    console.error("Erro ao carregar o PDF:", error);
});

if(sessionStorage.getItem("pageNumber") != null){
    let pagina = sessionStorage.getItem("pageNumber");
    document.getElementById("page-number").value = pagina;
}

function renderPage() {
    if (!pdfDoc) return;
    
    let pageNumber = parseInt(document.getElementById("page-number").value);
    sessionStorage.setItem("pageNumber", pageNumber);

    if (pageNumber < 1 || pageNumber > pdfDoc.numPages) {
        alert("Número de página inválido!");
        return;
    }

    pdfDoc.getPage(pageNumber).then(page => {
        const scale = 1.5;
        const viewport = page.getViewport({ scale });
        const canvas = document.getElementById("pdf-canvas");
        const context = canvas.getContext("2d");

        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };
        page.render(renderContext);
    });
}

function mudaPagina(qual) {
    let visor = document.getElementById("page-number");
    let numero = parseInt(visor.value);
    if(qual == "<" && numero > 1){
        visor.value = numero - 1;
    }
    if(qual == ">"){
        visor.value = numero + 1;
    }
}

function visualizarDiv(elemento){
    const conteudo = document.getElementById("conteudo");
    const editorhtml = document.getElementById("editorhtml");
    const editorcss = document.getElementById("editorcss");
    switch(elemento){
        case 1:
            if(!editorcss.hasAttribute("hidden")){
                editorcss.setAttribute("hidden", "");
            }
            if(!editorhtml.hasAttribute("hidden")){
                editorhtml.setAttribute("hidden", "");
            }

            if(conteudo.hasAttribute("hidden")){
                conteudo.removeAttribute("hidden");
            }
            break;
        case 2:
            if(!conteudo.hasAttribute("hidden")){
                conteudo.setAttribute("hidden", "");
            }
            if(!editorcss.hasAttribute("hidden")){
                editorcss.setAttribute("hidden", "");
            }

            if(editorhtml.hasAttribute("hidden")){
                editorhtml.removeAttribute("hidden");
            }
            break;
        case 3:
            if(!conteudo.hasAttribute("hidden")){
                conteudo.setAttribute("hidden", "");
            }
            if(!editorhtml.hasAttribute("hidden")){
                editorhtml.setAttribute("hidden", "");
            }

            if(editorcss.hasAttribute("hidden")){
                editorcss.removeAttribute("hidden");
            }
            break;
    }
}

// Para tab funcionar bem dentros dos textareas
document.getElementById('editorhtml').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
      e.preventDefault();
      var start = this.selectionStart;
      var end = this.selectionEnd;
  
      // set textarea value to: text before caret + tab + text after caret
      this.value = this.value.substring(0, start) +
        "\t" + this.value.substring(end);
  
      // put caret at right position again
      this.selectionStart =
        this.selectionEnd = start + 1;
    }
  });
  document.getElementById('editorcss').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
      e.preventDefault();
      var start = this.selectionStart;
      var end = this.selectionEnd;
  
      // set textarea value to: text before caret + tab + text after caret
      this.value = this.value.substring(0, start) +
        "\t" + this.value.substring(end);
  
      // put caret at right position again
      this.selectionStart =
        this.selectionEnd = start + 1;
    }
  });

// Recarregar iframe
document.getElementsByTagName("visor").contentWindow.location.reload();


// Ajustar escala do iframe
function ajustarZoom(valor){
    const iframe = document.getElementById("conteudo");
    let z = parseFloat(iframe.currentCSSZoom);

    if (valor == 1) {
        z = z + 0.1;
    }else{
        z = z - 0.1;
    }
    
    iframe.style.zoom = z;

}

