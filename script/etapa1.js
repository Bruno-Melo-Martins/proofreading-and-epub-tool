// Já começando zero bala
// CÓDIGO PARA O TAMANHO DO TEXTAREA
/*var editor = document.getElementById("editor");
editor.style.height = "";
editor.style.height = editor.scrollHeight + "px";

function readaptar(){
    var editor = document.getElementById("editor");
    editor.style.height = "";
    editor.style.height = editor.scrollHeight + "px";
}*/

// INSERIR TITULOS
function inserirTag(titulo, botao) {
    var editor = document.getElementById("editor");
    var curPos = editor.selectionStart;
    let x = editor.value; 

    if(botao.classList.contains("bt-1")){
        botao.classList.remove("bt-1");
        botao.classList.add("bt-2");
        editor.value = (x.slice(0, curPos)+ "<" + titulo + ">" + x.slice(curPos));

    }else{
        if(botao.classList.contains("bt-2")){
            botao.classList.remove("bt-2");
            botao.classList.add("bt-1");
            editor.value = (x.slice(0, curPos)+ "</" + titulo + ">" + x.slice(curPos));
            editor.selectionStart = curPos;
            editor.selectionEnd = curPos + 4;
        }
    }
} 
