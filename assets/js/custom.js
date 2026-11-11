// parse socket message with url
function parseMessage(message)
{
    let obj =  {'url':baseURL,'message':message};
    return JSON.stringify(obj);
}

function xss_filter(text){

    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

/*
when making changes in this file and pushing it to MASTER branch.
please also update the file in the CDN Assets folder.
*/