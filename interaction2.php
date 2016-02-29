<?php

session_start();
//Credentials for connecting to database.
$username="root";
$password="ra!nbow";
$host="localhost";
$database="AdaptiveWeb";

//connect to the database.

$server=mysqli_connect($host,$username,$password,$database);

$sqlcount="select count from counter;";

$result1=mysqli_fetch_assoc(mysqli_query($server,$sqlcount));

$count=$result1['count'];

if($count%2==0)
{
  //print_r($count);
  $user=$_GET['u_id'];
  $PS=0;
  $KS=0;
  $ML=0;
  $NA=0;
  $_SESSION["session_user"]=$user;
  $sql="select distinct intention, count(intention) as CountOf from query where u_id like '%".$user."%' group by intention";
  $result=mysqli_query($server,$sql);
  $intention=NULL;
  while ($row=mysqli_fetch_assoc($result)) {
    $intention=$row;  
    if($intention['intention']=='PS'){
      $PS=$intention['CountOf'];
    }
    elseif($intention['intention']=='KS'){
      $KS=$intention['CountOf'];
    }
    elseif($intention['intention']=='ML'){
      $ML=$intention['CountOf'];
    }
    elseif($intention['intention']=='NA'){
      $NA=$intention['CountOf'];
    }

  }

  $_SESSION["PS"]=$PS;
  $_SESSION["KS"]=$KS;
  $_SESSION["ML"]=$ML;
  $_SESSION["NA"]=$NA;
  $sql2="select * from wordCount where u_id like '%".$user."%'";
  $result2=mysqli_query($server,$sql2);
  $word=NULL;

  while ($row=mysqli_fetch_assoc($result2)) {
    $word=$row; 

  }
  $keys=array_keys($word);
  unset($word[$keys[0]]);
  arsort($word,SORT_NUMERIC);
  json_encode($word,JSON_PRETTY_PRINT);
  $_SESSION["word"]=$word;
  $operation=NULL;
  $TC=0;
  $SD=0;
  $SU=0;
  $SE=0;
  $sqlop="select distinct operation, count(operation) as CountOf from operations where u_id like '%".$user."%' group by operation;";
  $resultop=mysqli_query($server,$sqlop);
  while ($row1=mysqli_fetch_assoc($resultop)) {
    $operation=$row1; 
    if($operation['operation']=='"target_clicked"'){
      $TC=$operation['CountOf'];
    }
    elseif($operation['operation']=='"scroll_down"'){
      $SD=$operation['CountOf'];
    }
    elseif($operation['operation']=='"scroll_up"'){
      $SU=$operation['CountOf'];
    }
    elseif($operation['operation']=='"select"'){
      $SE=$operation['CountOf'];
    } 
  }
  $_SESSION["target_clicked"]=$TC;
  $_SESSION["scroll_down"]=$SD;
  $_SESSION["scroll_up"]=$SU;
  $_SESSION["select"]=$SE;

  $TOTAL=$_SESSION["target_clicked"]+$_SESSION["scroll_down"]+$_SESSION["scroll_up"]+$_SESSION["select"];
  $Knowledge_factor= ($_SESSION["KS"]+(3*$_SESSION["PS"])+(5*$_SESSION["ML"])+(30*$_SESSION["select"])+(10*$_SESSION["target_clicked"])+((abs($_SESSION["scroll_down"]-$_SESSION["scroll_up"]))/$TOTAL)*100);
  $_SESSION['KF']=$Knowledge_factor;

}
else
{
   //print_r($count);
  $user1=$_GET['u_id'];
  $PS=0;
  $KS=0;
  $ML=0;
  $NA=0;
  $_SESSION["session_user1"]=$user1;
  $sql="select distinct intention, count(intention) as CountOf from query where u_id like '%".$user1."%' group by intention";
  $result=mysqli_query($server,$sql);
  $intention=NULL;
  while ($row=mysqli_fetch_assoc($result)) {
    $intention=$row;  
    if($intention['intention']=='PS'){
      $PS=$intention['CountOf'];
    }
    elseif($intention['intention']=='KS'){
      $KS=$intention['CountOf'];
    }
    elseif($intention['intention']=='ML'){
      $ML=$intention['CountOf'];
    }
    elseif($intention['intention']=='NA'){
      $NA=$intention['CountOf'];
    }

  }

  $_SESSION["PS1"]=$PS;
  $_SESSION["KS1"]=$KS;
  $_SESSION["ML1"]=$ML;
  $_SESSION["NA1"]=$NA;
  $sql2="select * from wordCount where u_id like '%".$user1."%'";
  $result2=mysqli_query($server,$sql2);
  $word=NULL;

  while ($row=mysqli_fetch_assoc($result2)) {
    $word=$row; 

  }
  $keys=array_keys($word);
  unset($word[$keys[0]]);
  arsort($word,SORT_NUMERIC);
  json_encode($word,JSON_PRETTY_PRINT);
  $_SESSION["word1"]=$word;
  $operation=NULL;
  $TC=0;
  $SD=0;
  $SU=0;
  $SE=0;
  $sqlop="select distinct operation, count(operation) as CountOf from operations where u_id like '%".$user1."%' group by operation;";
  $resultop=mysqli_query($server,$sqlop);
  while ($row1=mysqli_fetch_assoc($resultop)) {
    $operation=$row1; 
    if($operation['operation']=='"target_clicked"'){
      $TC=$operation['CountOf'];
    }
    elseif($operation['operation']=='"scroll_down"'){
      $SD=$operation['CountOf'];
    }
    elseif($operation['operation']=='"scroll_up"'){
      $SU=$operation['CountOf'];
    }
    elseif($operation['operation']=='"select"'){
      $SE=$operation['CountOf'];
    } 
  }
  $_SESSION["target_clicked1"]=$TC;
  $_SESSION["scroll_down1"]=$SD;
  $_SESSION["scroll_up1"]=$SU;
  $_SESSION["select1"]=$SE;
  $TOTAL1=$_SESSION["target_clicked1"]+$_SESSION["scroll_down1"]+$_SESSION["scroll_up1"]+$_SESSION["select1"];
  $Knowledge_factor1= ($_SESSION["KS1"]+(3*$_SESSION["PS1"])+(5*$_SESSION["ML1"])+(30*$_SESSION["select1"])+(10*$_SESSION["target_clicked1"])+((abs($_SESSION["scroll_down1"]-$_SESSION["scroll_up1"]))/$TOTAL)*100);
  $_SESSION['KF1']=$Knowledge_factor1;
}


$count=$count+1;

$sqlupdate="update counter set count=".$count.";";
mysqli_query($server,$sqlupdate);


$sqlword="select * from Alluser";
$resultall=mysqli_query($server,$sqlword);
  while ($row=mysqli_fetch_assoc($resultall)) {

  $rows1[]=$row['u_id'];
  $rows2[]=$row['WordFreq'];

  }


$data=array();
for($i=0;$i<sizeof($rows1);$i++){
  $rows2[$i]=explode(" ", $rows2[$i]);
  $A=array(
    0=>$rows1[$i],1=>$rows2[$i]
  );
  array_push($data, $A);
}

$data=json_encode($data);



mysqli_close($server);

echo '

<html lang="en">
<head>
  <title>Interaction 2</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>    
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link href="https://pi.asu.edu/AdaptiveWeb/stylesheets/jquery.cssemoticons.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="https://pi.asu.edu/AdaptiveWeb/javascripts/jquery-1.4.2.min.js" type="text/javascript"></script>
  <script src="https://pi.asu.edu/AdaptiveWeb/javascripts/jquery.cssemoticons.js" type="text/javascript"></script>
  <style>
    /* Remove the navbar"s default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    svg {
    font: 12px sans-serif;
  }

text {
    pointer-events: none;
  }

.inner_node rect {
    pointer-events: all;
  }

.inner_node rect.highlight {
    stroke: #315B7E;
    stroke-width: 2px;
  }

#visualisation1{
  float:left;
}

#visualisation2{
    float:right;
    background-image: url("./papyrus.jpg");
    width:100%;
    border: 1px solid;
    background-size:contain;
   
}



.outer_node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
    pointer-events: all;
  }

.outer_node circle.highlight
{
    stroke: #315B7E;
    stroke-width: 2px;
}

.link {
    fill: none;
}


 
  </style>
</head>

<body>
<script src="https://d3js.org/d3.v3.min.js"></script>



<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Assignment 1</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="https://pi.asu.edu/AdaptiveWeb/interaction1.php">Single user Interaction(Click on me)</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="rows">
  <div class="col-md-6">
<div id="visualisation1">
  </div>
</div>
<div class="col-md-3">
  <div id="visualisation2">
    <div class="md"><h4></u>Comparing 2 student\'s activity :)</u></h4></div>
    <p><b>Name of the student:'.$_SESSION["session_user"].'</script></b></p>
    <p><b>Operation\'s performed by student</b></p>
    <ul style="list-style-type:disc">
      <li>Select:<b>'.$_SESSION["select"].'</b></li>
      <li>Scroll Down:<b>'.$_SESSION["scroll_down"].'</b></li>
      <li>Scroll Up:<b>'.$_SESSION["scroll_up"].'</b></li>
      <li>Target Clicked:<b>'.$_SESSION["target_clicked"].'</b></li>
    </ul>
    <p><b>Intention of the student</b></p>
    <ul style="list-style-type:disc">
      <li>Knowledge Seeking:<b>'.$_SESSION["KS"].'</b></li>
      <li>Problem Solving:<b>'.$_SESSION["PS"].'</b></li>
      <li>Method Learning:<b>'.$_SESSION["ML"].'</b></li>
      <li>Others:<b>'.$_SESSION["NA"].'</b></li>
    </ul>

    <p><b>Knowledge factor</b></p>
    <p><b>'.$_SESSION['KF'].'.';

    if($_SESSION['KF']<=200){
      echo '<div>RATING:BAD</div>';
    }
    elseif ($_SESSION['KF']>200 && $_SESSION['KF']<=800)  {
      echo '<div>RATING:AVERAGE</div>';
    }
    elseif ($_SESSION['KF']>700 && $_SESSION['KF']<1300)  {
      echo '<div>RATING:GOOD</div>';
    }
    else{
      echo '<div>RATING:GREAT</div>';
    }

    echo'
    </b></p>
    <p><b>Name of the student:'.$_SESSION["session_user1"].'</script></b></p>
    <p><b>Operation\'s performed by student</b></p>
    <ul style="list-style-type:disc">
      <li>Select:<b>'.$_SESSION["select1"].'</b></li>
      <li>Scroll Down:<b>'.$_SESSION["scroll_down1"].'</b></li>
      <li>Scroll Up:<b>'.$_SESSION["scroll_up1"].'</b></li>
      <li>Target Clicked:<b>'.$_SESSION["target_clicked1"].'</b></li>
    </ul>
    <p><b>Intention of the student</b></p>
    <ul style="list-style-type:disc">
      <li>Knowledge Seeking:<b>'.$_SESSION["KS1"].'</b></li>
      <li>Problem Solving:<b>'.$_SESSION["PS1"].'</b></li>
      <li>Method Learning:<b>'.$_SESSION["ML1"].'</b></li>
      <li>Others:<b>'.$_SESSION["NA1"].'</b></li>
    </ul>

    <p><b>Knowledge factor</b></p>
    <p><b>'.$_SESSION['KF1'].'.';
    if($_SESSION['KF1']<=200){
      echo '<div>RATING:BAD</div>';
    }
    elseif ($_SESSION['KF1']>200 && $_SESSION['KF1']<=800)  {
      echo '<div>RATING:AVERAGE</div>';
    }
    elseif ($_SESSION['KF1']>700 && $_SESSION['KF1']<1300)  {
      echo '<div>RATING:GOOD</div>';
    }
    else{
      echo '<div>RATING:GREAT</div>';
    }


    echo
    '</b></p>
    </div>
  </div>
  <div class="col-md-3">
  <div>
  <h4><b><u>Finding\'s multi user Student Interaction</u></b></h4>
  <h5><p><b>1.Content mapping of all student\'s</p></b></h5>
  <ul><b>The First Interaction is a content map which is used to show the keywords used by the student 
  while checking the stackoverflow website.</b></ul>
  <h5><p><b>2.Displpay two student comparison @ stack overflow</p></b></h5>
  <ul><b>The Projection papyrus board is used to display the 2 student\'s searching pattern and 
  their intentions and operation metrics during the time at stack overflow.</b></ul>
  <h5><p><b>3.Show Knowledge factor and Rating</p></b></h5>
  <ul><b>The knowledge factor a performance metric given to students on the basis of judging them 
  on important operations and intention to evaluate their performance as comapred to others.</ul>
  <ul>Performance Factor Formula:</b><i>(K.S+3*P.S+5*M.L+30*Select+10*TargetClicked+(abs(ScrollUp-ScrollDown)/TotalOperationcount)*100</i></ul>
  </ul>
  <ul><b>Ratings were Decided on Performance factor.</b>
    <li><=200:BAD</li>
    <li>>200 and <=800:AVERAGE</li>
    <li>>800 and <1300:GOOD</li>
    <li>>1300:GREAT</li>
    </ul>
<p>P.S:The numbers were found using the mean of all student\'s.</p>
    <br>

  </div>
  </div>

</div>

</div>
</body>

<script>

var data=JSON.parse(JSON.stringify('.$data.'));

console.log(data);

var outer = d3.map();
var inner = [];
var links = [];

var outerId = [0];

data.forEach(function(d){
  
  if (d == null)
    return;
  
  i = { id: "i" + inner.length, name: d[0], related_links: [] };
  i.related_nodes = [i.id];
  inner.push(i);

  
  if (!Array.isArray(d[1]))
    d[1] = [d[1]];


  d[1].forEach(function(d1){
    
    o = outer.get(d1);
  
    if (o == null)
    {
      o = { name: d1, id: "o" + outerId[0], related_links: [] };
      o.related_nodes = [o.id];
      outerId[0] = outerId[0] + 1;  
      
      outer.set(d1, o);
    }
    
    // create the links 
    l = { id: "l-" + i.id + "-" + o.id, inner: i, outer: o }
    links.push(l);
    
    // and the relationships
    i.related_nodes.push(o.id);
    i.related_links.push(l.id);
    o.related_nodes.push(i.id);
    o.related_links.push(l.id);
  });
});

data = {
  inner: inner,
  outer: outer.values(),
  links: links
}

// sort the data -- TODO: have multiple sort options
outer = data.outer;
data.outer = Array(outer.length);


var i1 = 0;
var i2 = outer.length - 1;

for (var i = 0; i < data.outer.length; ++i)
{
  if (i % 2 == 1)
    data.outer[i2--] = outer[i];
  else
    data.outer[i1++] = outer[i];
}





var diameter = 700;
var rect_width = 60;
var rect_height = 12;

var link_width = "1px"; 

var il = data.inner.length;
var ol = data.outer.length;

var inner_y = d3.scale.linear()
    .domain([0, il])
    .range([-(il * rect_height)/2, (il * rect_height)/2]);

mid = (data.outer.length/2.0)
var outer_x = d3.scale.linear()
    .domain([0, mid, mid, data.outer.length])
    .range([15, 170, 190 ,355]);

var outer_y = d3.scale.linear()
    .domain([0, data.outer.length])
    .range([0, diameter / 2 - 120]);


// setup positioning
data.outer = data.outer.map(function(d, i) { 
    d.x = outer_x(i);
    d.y = diameter/3;
    return d;
});

data.inner = data.inner.map(function(d, i) { 
    d.x = -(rect_width / 2);
    d.y = inner_y(i);
    return d;
});




// Can"t just use d3.svg.diagonal because one edge is in normal space, the
// other edge is in radial space. Since we can"t just ask d3 to do projection
// of a single point, do it ourselves the same way d3 would do it.  


function projectX(x)
{
    return ((x - 90) / 180 * Math.PI) - (Math.PI/2);
}

var diagonal = d3.svg.diagonal()
    .source(function(d) { return {"x": d.outer.y * Math.cos(projectX(d.outer.x)), 
                                  "y": -d.outer.y * Math.sin(projectX(d.outer.x))}; })            
    .target(function(d) { return {"x": d.inner.y + rect_height/2,
                                  "y": d.outer.x > 180 ? d.inner.x : d.inner.x + rect_width}; })
    .projection(function(d) { return [d.y, d.x]; });


var svg = d3.select("#visualisation1").append("svg")
    .attr("width", diameter)
    .attr("height", diameter+100)
  .append("g")
    .attr("transform", "translate(" + (diameter / 2)+ "," + (diameter / 2 + 30)+ ")");
    

// links
var link = svg.append("g").attr("class", "links").selectAll(".link")
    .data(data.links)
  .enter().append("path")
    .attr("class", "link")
    .attr("id", function(d) { return d.id })
    .attr("d", diagonal)
    .attr("stroke", "#E5ECFF")
    .attr("stroke-width", link_width);

// outer nodes

var onode = svg.append("g").selectAll(".outer_node")
    .data(data.outer)
  .enter().append("g")
    .attr("class", "outer_node")
    .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + (d.y) + ")"; })
    .on("mouseover", mouseover)
    .on("mouseout", mouseout);
  
onode.append("circle")
    .attr("id", function(d) { return d.id })
    .attr("r", 4.5);
  
onode.append("circle")
    .attr("r", 20)
    .attr("visibility", "hidden");
  
onode.append("text")
  .attr("id", function(d) { return d.id + "-txt"; })
    .attr("dy", ".31em")
    .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
    .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
    .text(function(d) { return d.name; });
  
// inner nodes
  
var inode = svg.append("g").selectAll(".inner_node")
    .data(data.inner)
  .enter().append("g")
    .attr("class", "inner_node")
    .attr("transform", function(d, i) { return "translate(" + d.x + "," + d.y + ")"})
    .on("mouseover", mouseover)
    .on("mouseout", mouseout)
    .on("click",click);
  
inode.append("rect")
    .attr("width", rect_width)
    .attr("height", rect_height)
    .attr("id", function(d) { return d.id; })
    .attr("fill","#DEC89F");
  
inode.append("text")
  .attr("id", function(d) { return d.id + "-txt"; })
    .attr("text-anchor", "middle")
    .attr("transform", "translate(" + rect_width/2 + ", " + rect_height * .75 + ")")
    .text(function(d) { return d.name; });

// need to specify x/y/etc

d3.select(self.frameElement).style("height", diameter - 150 + "px");


function click(d){

var a =d.name;
//if nothing do this
window.location.href = window.location.href+"?u_id="+d.name;


}



function mouseover(d)
{

  // bring to front
  d3.selectAll(".links .link").sort(function(a, b){ return d.related_links.indexOf(a.id); }); 
  
    for (var i = 0; i < d.related_nodes.length; i++)
    {
        d3.select("#" + d.related_nodes[i]).classed("highlight", true);
        d3.select("#" + d.related_nodes[i] + "-txt").attr("font-weight", "bold");
    }
    
    for (var i = 0; i < d.related_links.length; i++)
        d3.select("#" + d.related_links[i]).attr("stroke", "#C13AC2").attr("stroke-width", "5px");
}

function mouseout(d)
{     
    for (var i = 0; i < d.related_nodes.length; i++)
    {
        d3.select("#" + d.related_nodes[i]).classed("highlight", false);
        d3.select("#" + d.related_nodes[i] + "-txt").attr("font-weight", "normal");
    }
    
    for (var i = 0; i < d.related_links.length; i++)
        d3.select("#" + d.related_links[i]).attr("stroke", "#E5ECFF").attr("stroke-width", link_width);
}

if(typeof window.history.pushState == "function") {
        window.history.pushState({}, "Hide", "interaction2.php");
    }


$(".md").emoticonize();


</script>
</html>';







