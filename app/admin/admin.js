/* - - - - - - - - - - - - - -
  - Read url list as json
  - fetch structured data for each url
  - build & save global Json
- - - - - - - - - - - - - - */
  // var service='microlink.io';
  var service='embed.rocks';
  var dataPath='../data/';
  var url_list =  'google-sheets.json';
  var globalJson =  'db.json';
  var urlCount;

  url_list =  dataPath + url_list;
  globalJson =  dataPath + globalJson;
let parsedUrls=0;


/*
  Get url list as json,
  fetch structured data for each element
  build global json
*/

  $( "#fetch_embed_info" ).on( "click", function(){
    parsedUrls=0;
    var ourRequest = new XMLHttpRequest();
    ourRequest.open('GET', url_list, true);
    ourRequest.onload = function(){
      var Data = JSON.parse(ourRequest.responseText);
      urlCount = Data.posts.length;
       $( '#status ').append(urlCount + ' items found <br>');
       // $( '#relative_progress').css('width', urlCount + 'em');
       $( '<style>#relative_progress span { width: ' +(100/urlCount-.1).toFixed(2)+'%; margin: .05%; }</style>' ).appendTo( "head" );
       $('#progress_counter').html('0/'+urlCount);
      for (var i in Data.posts) {
        console.log(Data.posts[i]);
        console.log(service);
        fetchStructData(Data.posts[i], service);

      }
    }
    ourRequest.send();

  });

/*
  Write global JSON to file
*/

  $("#generate_json").on( "click", function(){
    parsedUrls=0; urlCount=1;
    $( '#relative_progress ').html('');
    phpBro('generate-main-json.php', { action: "write"});
  });

  $("#fetch_google_sheets").on( "click", function(){
    parsedUrls=0; urlCount=1; 
    $( '#relative_progress ').html('');
    phpBro('fetch-google-sheets.php', { action: "write"});
  });
$("#fetch_icons").on( "click", function(){
    // parsedUrls=0; urlCount=1; 
    $( '#relative_progress ').html('');
  phpBro('fetch-icons.php', { action: "write"});
  });
$("#generate_html").on( "click", function(){
    // parsedUrls=0; urlCount=1; 
    $( '#relative_progress ').html('');
  phpBro('render-html2020.php', { action: "write"});
  });

    $("#renderHTML a").on( "click", function(){
      parsedUrls=0; urlCount=1;
      xtarget = $(this).attr("target_file");
      xcollection = $(this).attr("collection");
      $( '#relative_progress ').html('');
    phpBro('render.php', { action: "write", target: xtarget, collection: xcollection});

  });


// https://stackoverflow.com/questions/18614301/keep-overflow-div-scrolled-to-bottom-unless-user-scrolls-up

var element = document.getElementById("status");
element.scrollTop = element.scrollHeight;
setInterval(updateScroll,500);
function updateScroll(){
    var element = document.getElementById("status");
    element.scrollTop = element.scrollHeight;

}


/* - - - - - - - - - - - - - -
      Functions
- - - - - - - - - - - - - - */

/*
  calls php script, returns answer
  https://stackoverflow.com/a/3548842/107671
    phpScript - path to php file
    params    - array - any data you want to send to the script
*/

  function phpBro(phpScript, params){
    $.get(
      phpScript, // location of your php script
      params, // any data you want to send to the script - array
      function( data ){  // a function to deal with the returned information
        var start_time = performance.now();
        $( '#status ').append(data);
        $( '#progress ').append('&middot;');
        var end_time=performance.now();
        $( '#status ').append(' <b>&rsaquo;</b><span class="red">' + (end_time - start_time).toFixed(4) + "<small>ms<br></small></span>"  );
         $( '#relative_progress ').append('<span>&nbsp;</span>');
        start_time = end_time;
        parsedUrls++;
        $('#progress_counter').html(parsedUrls +'/'+urlCount);
      }
    );

  }

  function fetchStructData(data, service){
    var opts =
    {
      action: "write",
      url: data.url,
      timestamp: data.Timestamp,
      comment: data.description,
      collections: data.tags,
      options: data.Options,
      service: service
    };
    $( '#relative_progress ').html('');
    phpBro('fetch-single-embed-info.php', opts);
  }
