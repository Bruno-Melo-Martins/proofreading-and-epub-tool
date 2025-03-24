// Já começando zero bala
// Código do CodeMirror
var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
    mode: "htmlmixed",
    theme: "dark",
    lineNumbers: true,
    indentWithTabs: true,
    tabSize: 2
});

// INSERIR TITULOS
function inserirTag(titulo, botao) {
    var editor = window.editor; // Obtém o objeto CodeMirror global
    var doc = editor.getDoc();  // Acessa o documento do editor

    var cursor = doc.getCursor(); // Obtém a posição do cursor
    var curPos = { line: cursor.line, ch: cursor.ch }; // Posição exata do cursor
    //alert("Posição do cursor: " + JSON.stringify(curPos));

    let x = doc.getValue(); // Obtém o texto atual do editor

    if (botao.classList.contains("bt-1")) {
        botao.classList.remove("bt-1");
        botao.classList.add("bt-2");

        // Insere a tag de abertura na posição do cursor
        doc.replaceRange("<" + titulo + ">", curPos);
    } else {
        if (botao.classList.contains("bt-2")) {
            botao.classList.remove("bt-2");
            botao.classList.add("bt-1");

            // Insere a tag de fechamento
            var fechamento = titulo.includes("div") ? "</div>" : "</" + titulo + ">";
            doc.replaceRange(fechamento, doc.getCursor()); // Insere na posição atual do cursor
        }
    }
}
