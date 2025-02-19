// Já começando zero bala
// CÓDIGO PARA O TAMANHO DO TEXTAREA
var editor = document.getElementById("editor");
editor.style.height = "";
editor.style.height = editor.scrollHeight + "px";

function readaptar(){
    var editor = document.getElementById("editor");
    editor.style.height = "";
    editor.style.height = editor.scrollHeight + "px";
}

// INSERIR TITULOS
function inserir(titulo) {
    var editor = document.getElementById("editor");
    var curPos = editor.selectionStart; 

    let x = editor.value; 

    editor.value = (x.slice(0, curPos)+ titulo + x.slice(curPos));
} 
