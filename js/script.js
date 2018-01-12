var $$ = Dom7;

var modal = document.getElementById('myModal');
var spanClose = document.getElementsByClassName("close")[0];

spanClose.onclick = function() {
    modal.style.display = "none";
};

$$(document).on('submit', '.mkWrite', function (e) {
    e.preventDefault();
    makePostRequest(e, $$(this));
});

$$(document).on('click', '.mkWriteButton', function (e) {
    makePostRequest(e, $$(this));
});

$$(document).on('click', '.mkMakeReply', function (e) {
    if(checkAuth()) {
        $$('.mkCommentReply').hide();
        var cid = $$(this).data('comment');
        var pid = $$(this).data('pid');
        var frm = $$('#mkForm');
        $$('#replyComment' + cid).html(frm.html());
        $$('#replyComment' + cid).find('.mkWrite').attr('data-comment', cid);
        $$('#replyComment' + cid).find('.mkWrite').attr('data-pid', pid);
        $$('#replyComment' + cid).show();
    }

});

$$(document).on('click', '.show-comments', function (e) {
    var cid = $$(this).data('comment');
    $$(this).remove();
    if(cid > 0) {
        getComments(cid);
    }

    var offset = $$(this).data('offset');
    if(offset > 0) {
        $$.get('api/comments/', {offset: offset}, function (data) {
            $$('#containerNext'+offset).html(data);
        });

    }
});

$$(document).on('click', '.hide-comments', function (e) {
    var cid = $$(this).data('comment');
    getComments(cid, true);
});

function getComments(cid, limit) {
    if(typeof limit == "undefined") {
        limit = 0;
    }
    $$.get('api/comments/'+cid, {limit: limit}, function (data) {
        $$('.commentsBlock'+cid).html(data);

        if(limit){
            $$('.hide'+cid).hide();
        }
        else {
            var height = $$('.commentsBlock'+cid).height();
            $$('.hide'+cid).css('height', height+'px').show();
        }
    });
}

function makePostRequest(e, self) {
    e.preventDefault();
    if(checkAuth()) {
        var frm;

        if(self.hasClass('mkWrite')) {
            frm = self;
        }
        else {
            frm = self.parents('.mkWrite');
        }

        var action = frm.attr('action'),
            commentId = frm.attr('data-comment'),
            pid = frm.attr('data-pid'),
            msg = frm.find('.mkText').val();

        $$.post(action, {msg:msg, reply: commentId, parentId: pid}, function (data) {
            if(pid > 0) {
                $$('#replyComment' + commentId).hide();
                getComments(pid, true);
            }
            else {
                $$.get('api/comments/',function (data) {
                    $$('.comments-list').html(data);
                });
            }
            frm.find('.mkText').val('');
        });
    }

}

function checkAuth() {
    if(typeof userId == "undefined")
    {
        $$.get('/api/auth/', function (data) {
            $$('#myModal p').html(data);
        });

        modal.style.display = "block";
        return false;
    }
    return true;
}

function dropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {

        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (event.target == modal) {
        modal.style.display = "none";
    }
}