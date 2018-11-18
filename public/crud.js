var base_url = "http://localhost:8000/api";

$(document).ready(function() {
    list();

    $('#list-body').on('click', '.delete-button', function() {
        var id = $(this).attr("data-id");
        destroy(id);
    });

    $('#list-body').on('click', '.edit-button', function() {
        var id = $(this).attr("data-id");
        edit(id);
    });

    $('#add').click(function() {
        formShow();
    });

    $('#form').submit(function(e) {
        e.preventDefault();

        let id = $("#id").val();

        if (id == "") {
            create();
        } else {
            update(id);
        }
        
    })
});

function list()
{
    $.ajax({
        type: "GET",
        url: `${base_url}/students`,
        contentType: "application/json",
        success: function(students) {
            let content = '';

            for (const student of students.data) {
                content += `
                    <tr>
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.birth}</td>
                        <td>${student.genre}</td>
                        <td>
                            <button class="edit-button" data-id="${student.id}">Editar</button>
                            <button class="delete-button" data-id="${student.id}">Excluir</button>
                        </td>
                    </tr>
                `
            }

            $('#list-body').html(content);
        }
    })
}

function destroy(id)
{
    $.ajax({
        type: "DELETE",
        url: `${base_url}/students/${id}`,
        contentType: "application/json",
        success: function() {
            alert('O aluno foi excluído com sucesso!');
            list();
        }
    });
}

function create()
{
    $.ajax({
        type: "POST",
        url: `${base_url}/students`,
        contentType: "application/json",
        dataType: 'json',
        data: JSON.stringify({
            "name": $("#name").val(),
            "birth": $("#birth").val(),
            "genre": $("#genre").find('option:selected').val(),
            "room_id": $("#room_id").val()
        }),
        success: function() {
            alert('O aluno foi criado com sucesso!');
            clearHideForm();
            list();
        },
        error: function(error) {
            showErrors(error);
        }
    });
}

function update(id)
{
    $.ajax({
        type: "PUT",
        url: `${base_url}/students/${id}`,
        contentType: "application/json",
        dataType: 'json',
        data: JSON.stringify({
            "name": $("#name").val(),
            "birth": $("#birth").val(),
            "genre": $("#genre").find('option:selected').val(),
            "room_id": $("#room_id").val()
        }),
        success: function() {
            alert('O aluno foi editado com sucesso!');
            clearHideForm();
            list();
        },
        error: function(error) {
            showErrors(error);
        }
    })
}

function edit(id)
{
    $.ajax({
        type: "GET",
        url: `${base_url}/students/${id}`,
        headers: {
            "Accept": "application/json"
        },
        contentType: "application/json",
        success: function(student) { 
            $("#id").val(student.data.id);   
            $("#name").val(student.data.name);
            $("#birth").val(student.data.birth);
            $("#genre").val(student.data.genre);
            $("#room_id").val(student.data.room.id);
        }
    });

    formShow();
}

function clearHideForm()
{
    $("#id").val('');
    $("#name").val('');
    $("#birth").val('');
    $("#genre").val($("genre option:first").val());
    $("#room_id").val('');
    $("#register").css('display', 'none');
}

function formShow()
{
    $("#register").css('display', 'block');
}

function showErrors(error)
{
    if(error.status == 422) {
        let errors = error.responseJSON;
        for (const key in errors) {
            alert(`${key}: ${errors[key]}`);
        }
    }
}