
// $(document).ready(function() {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//     $('#publishTask').click(function() {
//         $.ajax({
//             url: '/diplomas',
//             type: 'POST',
//             dataType: 'json',
//             data: {
//                 title: $('#title').val(),
//                 description: $('#description').val(),
//                 technologies:
//                     $('#technologies').val() ? $('#technologies').val() : '',
//                 group_id: $('#group').val()
//             },
//         })
//         .done(function(response) {
//             console.log(response);
//             $('#closeModal').click();
//             $('#title').val('');
//             $('#description').val('');
//             var diploma = '<tr><td>' + response.title+'</td></tr>';
//             $('#diplomas').append(diploma);
//         })
//         .fail(function(response) {
//             console.log(response.responseJSON);
//             if (response.responseJSON) {
//                 $('#titleErr').text(response.responseJSON.title);
//                 $('#titleGroup').addClass('has-error');
//             }
//             if (response.responseJSON) {
//                 $('#descrErr').text(response.responseJSON.description);
//                 $('#descriptionGroup').addClass('has-error');
//             }
//         });
//         $('#title').keyup(function() {
//             $('#titleErr').text('');
//             $('#titleGroup').removeClass('has-error');
//         });
//         $('#description').keyup(function() {
//             $('#descrErr').text('');
//             $('#descriptionGroup').removeClass('has-error');
//         });
//     });
//     $('#closeModal, #closeSign').click(function() {
//         $('#title').val('');
//         $('#description').val('');
//     });
//     $('#group').on('change',
//         function() {
//             $.ajax({
//                 url: '/diplomas/data',
//                 type: 'GET',
//                 dataType: 'json',
//                 data: {
//                     group_id: $('#group').val()
//                 },
//             })
//             .done(function(response) {
//                 console.log(response);
//             })
//             .fail(function(response) {
//                 console.log(response);
//             });
//         });
// });
