// Takanashi CMS Edit-article Script File
// (C)2016-2023 Takanashi.
// -------------------------------
// Using librarys
// -jQuery
// -Bootstrap
// -------------------------------
// version 1.04

//新規投稿ページ
if(document.getElementById('new-article')!=undefined){
    const form = document.getElementById('new-article');
    form.onsubmit = (e)=>{
        e.preventDefault();
        //処理に時間がかかることが予想されるため、Progress Barを配置する。
        document.getElementById('loading').classList.add('show');
        const form = document.getElementById('new-article');
        //Authorを取得する。
        $.post("/api/admin/login/get/",(data)=>{
            if(data.result==true){
                $.post("/api/admin/post/",{"author":data.data.name,"category":form.cat.value,"title":form.title.value,"body":form.body.value,"mode":"markdown"},(res)=>{
                    if(res.result==true){
                        document.getElementById('loading').classList.remove('show');
                        window.alert('記事を投稿しました。');
                        location.href = "/dashboard/article/";
                    }
                })
            }
        })
    }
    window.onload = function(){
        $.post("/api/admin/category/get/detail/",(data)=>{
            if(data.result==true){
                document.getElementById('datalistOptions').innerHTML = data.data;
            }
        })
    }
}

//記事一覧
if(document.getElementById('search-article-edit')!=undefined){
    const search = document.getElementById('search-article-edit');
    const control = document.getElementById('control-article');
    window.onload = ()=>{
        getArticle();
    }
    search.onsubmit = (e)=>{
        e.preventDefault();
        if(search.ast.value == undefined || search.ast.value == null || search.ast.value == ""){
            getArticle();
        }else{
            getArticleByName(search.ast.value);
        }
    }
    control.onsubmit = (e)=>{
        e.preventDefault();
    }
}

//カテゴリー一覧
if(document.getElementById('search-category-edit')!=undefined){
    const search = document.getElementById('search-category-edit');
    const control = document.getElementById('control-category');
    window.onload = ()=>{
        getCategory();
    }
    search.onsubmit = (e)=>{
        e.preventDefault();
        if(search.act.value == undefined || search.act.value == null || search.act.value == ""){
            getCategory();
        }else{
            getCategoryByName(search.act.value);
        }
    }
    control.onsubmit = (e)=>{
        e.preventDefault();
    }
}


function getArticle(){
    toggleLoading()
    $.post("/api/admin/get/",(data)=>{
        if(data.result==true){
            document.getElementById('article-list').innerHTML = data.data;
            toggleLoading(false);
        }
    });
}

function getArticleByName(title){
    toggleLoading();
    $.post("/api/admin/get/detail/",{"title":title},(data)=>{
        if(data.result==true){
            document.getElementById('article-list').innerHTML = data.data;
            toggleLoading(false);
        }
    });
}

function requestDelete(id){
    toggleLoading()
    $.post("/api/admin/edit/delete/",{"id":id},(data)=>{
        if(data.result==true){
            showModal("記事の削除","記事の削除に成功しました。");
            document.getElementById('search-article-edit').ast.value = "";
            getArticle();
            toggleLoading(false);
        }
    })
}

function toggleLoading(flug=true){
    if(flug == true){
        document.getElementById('loading').classList.add('show');
    }else{
        document.getElementById('loading').classList.remove('show');
    }
}

function view(id){
    window.open("/view/?id="+id);
}

function getCategory(){
    toggleLoading()
    $.post("/api/admin/category/get/",(data)=>{
        if(data.result==true){
            document.getElementById('category-list').innerHTML = data.data;
            toggleLoading(false);
        }
    });
}

function getCategoryByName(title){
    toggleLoading();
    $.post("/api/admin/category/get/dashboard/",{"title":title},(data)=>{
        if(data.result==true){
            document.getElementById('category-list').innerHTML = data.data;
            toggleLoading(false);
        }
    });
}

function requestDeleteCat(id){
    toggleLoading()
    $.post("/api/admin/edit/delete/category/",{"id":id},(data)=>{
        if(data.result==true){
            showModal("カテゴリの削除","カテゴリの削除に成功しました。");
            document.getElementById('search-category-edit').act.value = "";
            getCategory();
            toggleLoading(false);
        }
    })
}