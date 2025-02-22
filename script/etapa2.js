const pdfUrl = document.getElementById("linkdolivro").innerHTML; // Caminho do PDF
let pdfDoc = null;

pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
    pdfDoc = pdf;
    document.getElementById("page-number").max = pdf.numPages; // Define o máximo de páginas disponíveis
    renderPage(); // Renderiza a primeira página
}).catch(error => {
    console.error("Erro ao carregar o PDF:", error);
});

function renderPage() {
    if (!pdfDoc) return;
    
    let pageNumber = parseInt(document.getElementById("page-number").value);
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

function visualizarDiv(elemento){
    var conteudo = document.getElementById("conteudo");
    var editorhtml = document.getElementById("editorhtml");
    var editorcss = document.getElementById("editorcss");
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



/*function inserir(titulo) {
    var editor = document.getElementById("editor");
    var curPos = editor.selectionStart; 

    let x = editor.value; 

    editor.value = (x.slice(0, curPos)+ titulo + x.slice(curPos));
} 

function transformar(caminho){
    var div = document.getElementById('contents');
    var textarea = document.createElement('textarea');
    textarea.setAttribute("id", "editando");
    textarea.setAttribute("name", "editando");
    textarea.setAttribute("src", caminho);
    textarea.setAttribute("style", "width: "+div.offsetWidth+"px; height:"+div.offsetHeight+"px; font-family: monospace;");
    textarea.setAttribute("class", "conteudo");
    textarea.setAttribute("oninput", "readaptar()");
    div.replaceWith(textarea);
    var butao = document.getElementById("salva");
    butao.removeAttribute("disabled");
}

function salvado(caminho){
    var textarea = document.getElementById('editando');
    var div = document.createElement('iframe');
    div.setAttribute("src", caminho);
    div.setAttribute("class", "conteudo");
    //div.setAttribute('ondblclick', 'transformar()');
    //div.setAttribute("style", "text-align: justify; width: 50%")
    textarea.replaceWith(div);
    //div.innerHTML = texto;
    var butao = document.getElementById("salva");
    butao.setAttribute("disabled", "");
}
function readaptar(){
    var editor = document.getElementById("editando");
    editor.style.height = "";
    editor.style.height = editor.scrollHeight + "px";
}

function paginadolivro(){

}*/