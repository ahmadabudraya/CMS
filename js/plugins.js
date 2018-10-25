/*global $, alert, console*/
var turn = 90,f=1,L=0;
$('.accordion h3').click(function(){
    $(this).next('ul').slideToggle();
    $(this).find('.fa-angle-right').css({
           'transform':'rotate('+turn+'deg)' 
    });
    $('.accordion ul').not($(this).next('ul')).slideUp();

    
    $('.accordion h3 .fa-angle-right').not($(this)
                                      .find('.fa-angle-right'))
                                      .css({'transform':'rotate(0deg)'});
    
    turn = 90 - turn;
});

$('nav div .fa-angle-down').click(function(){
    $('nav .setting').slideToggle();
});

$(document).on('click','.confirm',function(e){
  console.log('YES');
  let res = confirm('Are U sure ?');
  var postid = e.target.dataset['postid'];
  if(res){
    $.ajax({
      url:'api_delete_post.php',
      method:'POST',
      data:{postid:postid}
    }).done(function(data){
      console.log(data);
        $(e.target.parentNode.parentNode).remove();
    });
  }
});


function can(flag){
  $('#register').prop("disabled",flag);  
}

function checking(id){
    var input = document.getElementById(id).value;
    if(input.length > 0){
        $.ajax({
            url:'api_checking.php',
            method:'POST',
            dataType:'json',
            data:{value:input,key:id}
        }).done(function(data){
            if(data[id]){
              can(false);
              $('#'+id).css('border-bottom-color','#58c791');
            }else{
              can(true);
              $('#'+id).css('border-bottom-color','red');
            }
        });
    }else{
        $('#'+id).css('border-bottom-color','#ebebeb');
    }
}

$('.approve').on('click',function(e){
  e.preventDefault();
  var userid = e.target.dataset['userid'];
  $(e.target.parentNode.parentNode).remove();
  $.ajax({
    url:'accept_user.php',
    method:'POST',
    data:{id:userid}
  }).done(function(data){
    console.log(data);
    console.log('DONE');
  });
});

$('#finduser').keyup(function(e){
  var input = e.target.value;
  //console.log(input);
  if(input.length > 0){
    $.ajax({
      url:'api_search_user.php',
      method:'POST',
      dataType:'json',
      data:{name:input}
    }).done(function(data){
      //console.log(data);
      $('.founduser').empty();
      $.each(data,function(i,v){
        $('.founduser').append("<tr><td>"+v+"</td></tr>");
      });
    });
  }else{
    $('.founduser').empty();
  }
});


$('.sort').click(function(){
  if($('.sort i').hasClass('fa-angle-down')){
    $('.sort i').removeClass('fa-angle-down').addClass('fa-angle-up');
  }else{
    $('.sort i').removeClass('fa-angle-up').addClass('fa-angle-down');
  }
  var n  = $('.table-posts tr').length;
  var ele =[];
  for(let j = 1; j<n; j++){
    ele.push($('.table-posts tr').eq(j).html());
  }
  for(let i = 1; i < n;i++){
    $('.table-posts tr').eq(i).html(ele[n-i-1]);
  }
});


$('#pass,#repass').keyup(function(){
  var pass   = $('#pass').val();
  var repass = $('#repass').val();
  if(pass.length > 0 && repass.length > 0){
    $('.validate .fa').css('display','inline-block');
    if(pass == repass){
      can(false);
      $('.validate .fa').addClass('fa-check').removeClass('fa-times');
    }else{
      can(true);
      $('.validate .fa').addClass('fa-times').removeClass('fa-check');
    }
  }else{
    can(true);
    $('.validate .fa').css('display','none');
  }

});

//var posts = [];
$('.paginate tr').each(function(i,v){
  
  //console.log(v);
  
  if(i>3){
    $(this).hide();
  }
});

$('.link .fa-angle-right').click(function(){
  let len = $('.paginate tr').length;
  L+=(L+4<len) ? 3 : 0;
  for(let i = 0; i<len; i++){
    
    if(i>=L && i<=L+2){
      $('.paginate tr').eq(i+1).fadeIn();
    }else{
      $('.paginate tr').eq(i+1).hide();
    }
  
  }
  
});

$('.link .fa-angle-left').click(function(){
  let len = $('.paginate tr').length;
  L+=(L-3>=0) ? -3 : 0;
  for(let i = 0; i<len; i++){
    
    if(i>=L && i<=L+2){
      $('.paginate tr').eq(i+1).fadeIn();
    }else{
      $('.paginate tr').eq(i+1).hide();
    }
    
  }
  
});

$(document).ready(function(){
  load_data();
  function load_data(page = 1){
    $.ajax({
      url:'api_pagination.php',
      method:'POST',
      dataType:'json',
      data:{page:page}
    }).done(function(data){
      $('.table-paginate').html(data.content);
      $('.paginator').html(data.links);
    });
  }

  $(document).on('click', '.paginator li', function(){  
    var page = $(this).attr('class');
    console.log(page);
    load_data(page);
  });
});


$('#submit').click(function(e){
  e.preventDefault();

  var form_data = $('#comment_form').serialize();
  $.ajax({
    url:'api_add_comment.php',
    method:'POST',
    data:form_data,
    dataType:'json'
  }).done(function(data){
    console.log(data);
    if(data.error != ''){
      $('#comment_form')[0].reset();
      $('#comment_message').html(data.error);
      load_comment();
    }
  });
  
});
load_comment();
function load_comment(){
  var post_id = $('#display_comment').data('postid');

  $.ajax({
    url:'api_fetch_comment.php',
    method:'post',
    data:{post_id:post_id}

  }).done(function(data){
    $('#display_comment').html(data);
  });
}

$(document).on('click','.reply',function(){
  var comment_id = $(this).attr('id');
  $('#comment_id').val(comment_id);
  $('#comment_name').focus();
});