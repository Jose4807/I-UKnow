function editarPublicacion(id, titulo, contenido) {
    document.getElementById('id_publicacion').value = id;
    document.getElementById('titulo').value = titulo;
    document.getElementById('contenido').value = contenido;
    var editarModal = new bootstrap.Modal(document.getElementById('editarModal'));
    editarModal.show();
}

