
<!DOCTYPE html>


<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LINEダイエットマネージャー </title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="Chart/Chart.js"></script>
    </head>
    <style>


    body{
     background-color: #808080;
     margin:0px;          
     padding:0px;         
     text-align:center;   
    }

    #main{
     margin-left:auto;    
     margin-right:auto;
     padding-top: 10px;
     padding-bottom: 10px;   
     text-align:left;    
     width:800px;
     background-color: white;         
    }

    p{
        margin: 0 auto;
    }

    </style>

<body style="overflow-y:hidden;overflow-x:auto;margin:0">
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">LINEダイエットマネージャー</a>
    </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">メイン</a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">メニュー<span     class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
             <li class="divider"></li>
             <li><a href="#">One more separated link</a></li>
             </ul>
            </li>
         </ul>
         <form class="navbar-form navbar-left" role="search">
         <div class="form-group">
             <input type="text" class="form-control" placeholder="Search">
         </div>
            <button type="submit" class="btn btn-default">Submit</button>
         </form>
         <ul class="nav navbar-nav navbar-right">
         <li><a href="#">Link</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span　class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
            </ul>
            </li>
        </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
    </nav>
    <div id = "main" >
        <h2><div align="center">あなたの情報</div></h2>
        <h3>最近1週間のグラフ</he>
        <canvas id="sample" height="300" width="770"></canvas>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>



                 if(window.location.search){
                    /* URLの「?」以降のパラメータを変数nに代入 */
                    url=window.location.search.substring(1,window.location.search.length);
                    url = url.substr(-8,8);
                    alert(url+"さんの情報になります");
                    
                 }
                         
            urlusr = "http://153.121.71.172/api/recodes/graph/" + url;
          $.ajax({
                    type: 'GET',
                    dataType:'json',
                    url:urlusr,
                    success:function(data) {
                        console.log(data._1.Menu.name);
                        console.log(data);

                        var nemui = 0;
                        var modified = new Array;
                        var menu = new Array;
                        var energy = new Array;

                        while(nemui < 20){
                          modified[nemui] = data["_"+nemui];
                          //modified[nemui] = "data._" + nemui + ".Menu.modified";
                          menu[nemui] = "data._" + nemui + ".Menu.name";
                          energy[nemui] = "data._" + nemui + ".Menu.energy";
                           nemui++;
                        }
                        console.log(modified);

                        nemui = 0;
                        while(nemui < 20){
                          $("div.log").append("<p>"+modified[nemui].Menu.modified+"に"+"<b>"+modified[nemui].Menu.name+"</b>"+"("+modified[nemui].Menu.energy+"kcal)を食べました"+"</p>");
                          nemui++;
                          //console.log(modified);
                        }
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("データ取得に失敗しました" + url);   
                    }
                });
        </script>
        <h3>最近20件の情報</h3>
        <div style="padding: 10px; margin-bottom: 10px; border: 5px double #333333;">
            <div style="width:770px;height:150px;overflow:auto;"　> 
              <div class = "log"></div>
            </div>
        </div>


     </div>

     
   
     <script src="js/bootstrap.min.js"></script>
     <script>
        var calorie = null;
        var day = null;
        var url = null;
        var urlusr = null;        
                 if(window.location.search){
                    /* URLの「?」以降のパラメータを変数nに代入 */
                    url=window.location.search.substring(1,window.location.search.length);
                    url = url.substr(-8,8);
                 }
                         
            urlusr = "http://153.121.71.172/api/recodes/graph/" + url;
            

                    $.ajax({
                    type: 'GET',
                    dataType:'json',
                    url:urlusr,
                    success:function(data) {
                        calorie = data.GraphData.calorie;
                        day = data.GraphData.day;
                        
                        console.log(day);
                        console.log(calorie);
                      　var lineChartData = {

                        labels : day ,
                          datasets : [
                        {
                          fillColor : "#00bfff",
                          strokeColor : "rgba(220,220,220,1)",
                          pointColor : "rgba(220,220,220,1)",
                          pointStrokeColor : "#00bfff",
                          data : calorie 
                        },
                  ]
              }
              var myLine = new Chart(document.getElementById("sample").getContext("2d")).Line(lineChartData);
                        
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("データ取得に失敗しました" + url);   
                    }
                });

    </script>
    <script>

             
    </script>

</body>
</html>