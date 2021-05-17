function initAutocomplete(){
    $( ".taskSearch" ).autocomplete({
        // source: content,
        source: async function( request, response ) {
    
     
            tasks = await callMethodAsyncTasks('tasks.task.list',{filter:{
                "?TITLE": request
            }})
            var  content = [];
            for (let i = 0; i < tasks.length; i++) {
                content.push({label:tasks[i].title,id:tasks[i].id});
            }
            response(content);
            // return content;
            // $.getJSON( "search.php", {
            //   term: extractLast( request.term )
            // }, response );
          },
          select: function(event,ui){
              debugger
              $(event.target).parent().find('.taskId').val(ui.item.id)
          }
      });
}
