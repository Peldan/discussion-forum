$( document ).ready(() => {
    //Hämtar information om forumets senaste aktivitet med hjälp av get_latest_activity.php. Skapar exempelvis länkar till de aktuella trådarna/svaren i högerkolumnen för
    //enkel navigering till de senaste inläggen. 
    let activityrequest = new XMLHttpRequest();
    activityrequest.addEventListener('load', response);
    let topicdiv = document.getElementsByClassName("latest_topics")[0];
    let postdiv = document.getElementsByClassName("latest_posts")[0];
    function response(){
        let response = JSON.parse(this.responseText);
        let tableInfoBar = document.createElement("div");
        let tableInfoBar2 = document.createElement("div");
        tableInfoBar.setAttribute("class", "tableInfoBar");
        tableInfoBar2.setAttribute("class", "tableInfoBar");
        tableInfoBar.innerHTML = "Senaste inläggen";
        tableInfoBar2.innerHTML = "Senaste trådarna";
        topicdiv.appendChild(tableInfoBar2);
        postdiv.appendChild(tableInfoBar);
        for(let i = 0; i < response['posts'].length; i++){
            let curr = response['posts'][i];
            let post = document.createElement("p");
            //let hr = document.createElement("hr");
            post.setAttribute("class", "tableDataCell");
            let link = "forums.php?category="+curr['category']+"&topic="+curr['topic'];
            post.innerHTML = '<a href='+link+'><strong>'+curr['author'] + '</strong> i diskussion: <strong>' + curr['topicname'] + '</strong> - ' + curr['created'] +'</a>'
            postdiv.appendChild(post);
            //postdiv.appendChild(hr);
        }
        for(let i = 0; i < response['topics'].length; i++){
            let curr = response['topics'][i];
            let topic = document.createElement("p");
            //let hr = document.createElement("hr");
            topic.setAttribute("class", "tableDataCell");
            let link = "forums.php?category="+curr['category']+"&topic="+curr['id'];
            topic.innerHTML = '<a href='+link+'><strong>'+curr['title'] + '</strong> av: <strong>' + curr['author'] + '</strong> - ' + curr['created'] +'</a>';
            topicdiv.appendChild(topic);
            //topicdiv.appendChild(hr);
        }
    }
    activityrequest.open('GET', 'script/get_latest_activity.php');
    activityrequest.send();
});
