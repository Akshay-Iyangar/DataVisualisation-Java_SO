<?php

if(isset($_POST['user_id'])){
	$value=$_POST['user_id'];

} 
else{
	$value="A0042";
}


//Credentials for connecting to database.
$username="root";
$password="ra!nbow";
$host="localhost";
$database="AdaptiveWeb";

//connect to the database.

$server=mysqli_connect($host,$username,$password,$database);


$sql="select distinct u_id from operations";


$result=mysqli_query($server,$sql);

$user_id=array();

if(!$result){
	echo "Seems to be an issue in connecting to the Database";
}

while ($row=mysqli_fetch_assoc($result)) {

	$user_id[]=$row;	

}

$key_word=["java","partition","abstract","accessor","action","actionevent","actionlistener","algorithms","array","cast","button",
"class","constructor","count","data","decimal","encapsulation","extends","for","function","inherit","input","iterate","panel","loop",
"merge","method","number","partition","morphism","sort","string","text","vector","while"

];


echo'
	<html>
	<head>
	<title>Interaction 1</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pi.asu.edu/AdaptiveWeb/jQCloud-master/dist/jqcloud.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://pi.asu.edu/AdaptiveWeb/jQCloud-master/dist/jqcloud.min.js"></script>
  <style>
    /* Remove the navbar"s default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

	
	path {  stroke: #fff; }
	path:hover {  opacity:0.9; }
	rect:hover {  fill:blue; }
	.axis {  font: 10px sans-serif; }
	.legend tr{    border-bottom:1px solid grey; }
	.legend tr:first-child{    border-top:1px solid grey; }

	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}

	.x.axis path {  display: none; }
	.legend{
	    margin-bottom:76px;
	    display:inline-block;
	    border-collapse: collapse;
	    border-spacing: 0px;
	}
	.legend td{
	    padding:4px 5px;
	    vertical-align:bottom;
	}
	.legendFreq, .legendPerc{
	    align:right;
	    width:50px;
	}

#visualisation3 {
  width:100%;
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
	        <li class="active"><a href="./interaction2.php">Multi user Interaction (Click on me)</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>

	
<form name="myform" action="#" method="post">
	<div class="row">
		<div class="col-md-offset-1 col-col-md-8">
		<b>Select Student ID</b><select name="user_id" class="user_id" id="user_id" onchange="myform.submit();">';
		for($i=0;$i<sizeof($user_id);$i++){

		echo '<option value='.$user_id[$i]["u_id"].'>'.str_replace('"', '', $user_id[$i]["u_id"]).'</option>';
	}

	echo'	
			</select>
		</div>
		</div>
</form>

<div class="row">
<h3><center><b><u>Dashboard for Student '.$value.'</u></b></center></h3>
</div>

<div class="row">
		<div class="col-md-8" id="visualisation1">
			
		</div>
		<div class="col-md-4" id="visualisation2">
		
		</div>
</div>
<div class="row">
    <div class="col-md-8">
        <div id="visualisation3"></div>
    </div>
    <div class="col-md-4">
        <div>
        <h4><b><u>Findings of Individual student </u></b></h4>
            <h5><b>1.Time Series analysis of the data<b></h5>
                <ul>To find the total count of operation\'s performed by the student during all time when they were on stack overflow</ul>
            <h5><b>2.Seperate Operation count of every Student.<b></h5>  
                <ul>To get count of Individual operation\'s performed by every student.</ul> 
            <h5><b>3.Display Word Cloud for every student<b></h5>  
                <ul>Search student\'s word cloud pattern during the complete time interval at stack overflow..</ul>
        </div>
    </div>
</div>

	</body>
</html>

	';


$sql1="select distinct timestamp from operations where u_id like '%".$value."%'";
$time_stamp=array();
$result1=mysqli_query($server,$sql1);
while ($row1=mysqli_fetch_assoc($result1)) {

	$time_stamp[]=$row1;	

}

for($i=0;$i<sizeof($time_stamp);$i++){
	${"operation_count".$i}=array();
	${"sql_op".$i} = "select distinct operation, count(operation) as CountOf from operations where u_id like '%".$value."%' and timestamp='".$time_stamp[$i]["timestamp"]."' group by operation;";
	${"result_op".$i}=mysqli_query($server,${"sql_op".$i});
	while ($row1=mysqli_fetch_assoc(${"result_op".$i})) {
		${"operation_count".$i}[]=$row1;	
	}
}

for($i=0;$i<sizeof($time_stamp);$i++){
	${"final_count".$i}=array();
	${"final_count".$i}[0]=0;
	${"final_count".$i}[1]=0;
	${"final_count".$i}[2]=0;
	${"final_count".$i}[3]=0;
	for($j=0;$j<sizeof(${"operation_count".$i});$j++){
		//print_r(${"operation_count".$i}[$j]["operation"]);
		if(${"operation_count".$i}[$j]["operation"]==="\"target_clicked\""){
			${"final_count".$i}[0]=${"operation_count".$i}[$j]['CountOf'];
		}
		elseif(${"operation_count".$i}[$j]["operation"]==="\"scroll_down\""){
			${"final_count".$i}[1]=${"operation_count".$i}[$j]['CountOf'];
		}
		elseif(${"operation_count".$i}[$j]["operation"]==="\"scroll_up\""){
			${"final_count".$i}[2]=${"operation_count".$i}[$j]['CountOf'];
		}
		elseif (${"operation_count".$i}[$j]["operation"]==="\"select\"") {
			${"final_count".$i}[3]=${"operation_count".$i}[$j]['CountOf'];
		}

	}

}
$data=array();
for($i=0;$i<sizeof($time_stamp);$i++){
	${"final_op".$i}=array(

			'Time'=>$time_stamp[$i]['timestamp'],'Operation'=>array('target_clicked'=>${"final_count".$i}[0],"scroll_down"=>${"final_count".$i}[1],"scroll_up"=>${"final_count".$i}[2],"select"=>${"final_count".$i}[3])

		);
	${"final_op".$i}=json_encode(${"final_op".$i});
	${"final_op".$i}= str_replace('"', '', ${"final_op".$i});
	array_push($data, ${"final_op".$i});
}

$data=json_encode($data);
$data= str_replace('"', '', $data);

$sqlword="select * from wordCount where u_id like '%".$value."%'";
$word=NULL;
$result=mysqli_query($server,$sqlword);
while ($row=mysqli_fetch_assoc($result)) {

    $word=$row;    

}
$keys=array_keys($word);

//print_r($word[$keys[1]]);
$word_final=array();
for($i=1;$i<sizeof($word);$i++){

    ${"word_corpus".$i-1}=array(

        'text'=>$keys[$i],'weight'=>$word[$keys[$i]]

        );
    
    array_push($word_final, ${"word_corpus".$i-1});

}

$word_final=json_encode($word_final);

echo'
	
<script>

var Data=JSON.parse(JSON.stringify('.$data.'));
var words=JSON.parse(JSON.stringify('.$word_final.'));

console.log(words);
function dashboard(id,id1,fData){
    var barColor = "#32B3C9";
    function segColor(c){ return {target_clicked:"#426C5D", scroll_down:"#C48D22",scroll_up:"#E2464E",select:"#6170CB"}[c]; }
    
    // compute total for each state.
    fData.forEach(function(d){
    	console.log(d.Operation);
    	d.total=d.Operation.target_clicked+d.Operation.scroll_down+d.Operation.scroll_up+d.Operation.select;});
    
    // function to handle histogram.
    function histoGram(fD){
        var hG={},    hGDim = {t: 60, r: 20, b: 90, l: 90};
        hGDim.w = 760 - hGDim.l - hGDim.r, 
        hGDim.h = 400 - hGDim.t - hGDim.b;
            
        //create svg for histogram.
        var hGsvg = d3.select(id).append("svg")
            .attr("width", hGDim.w + hGDim.l + hGDim.r)
            .attr("height", hGDim.h + hGDim.t + hGDim.b).append("g")
            .attr("transform", "translate(" + hGDim.l + "," + (hGDim.t) + ")");

        // create function for x-axis mapping.
        var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
                .domain(fD.map(function(d) { return d[0]; }));

        // Add x-axis to the histogram svg.

      	 hGsvg.append("g").attr("class", "x axis")
            .attr("transform", "translate(-20," + (hGDim.h+30) + ")")
            .call(d3.svg.axis().scale(x).orient("bottom"))
            .selectAll("text")
            .attr("transform", "rotate(-70)" );

        //Append title x-axis
         hGsvg.append("text")
            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
            .attr("transform", "translate("+ (hGDim.w/2) +","+335+")")  // centre below axis
            .style("font-weight", "bold")
            .style("font-size","25px")
            .text("Time");


        // Create function for y-axis map.
        var y = d3.scale.linear().range([hGDim.h, 0])
                .domain([0, d3.max(fD, function(d) { return d[1]; })]);

        //Add y-axis to the histogram svg.

         hGsvg.append("g").attr("class", "y axis")
         	.attr("transform", "translate(4,0)")
            .text("Total count")
            .call(d3.svg.axis().scale(y).orient("left"));

        //Append title
        hGsvg.append("text")
            .attr("text-anchor", "middle")  // this makes it easy to centre the text as the transform is applied to the anchor
            .attr("transform", "translate("+ -50 +","+(hGDim.h/2)+")rotate(-90)")  // text is drawn off the screen top left, move down and out and rotate
            .style("font-weight", "bold")
            .style("font-size","25px")
            .text("Total Count");

        // Create bars for histogram to contain rectangles and freq labels.
        var bars = hGsvg.selectAll(".bar").data(fD).enter()
                .append("g").attr("class", "bar");
        
        //create the rectangles.
        bars.append("rect")
            .attr("x", function(d) { return x(d[0]); })
            .attr("y", function(d) { return y(d[1]); })
            .attr("width", x.rangeBand())
            .attr("height", function(d) { return hGDim.h - y(d[1]); })
            .attr("fill",barColor)
            .on("mouseover",mouseover)// mouseover is defined below.
            .on("mouseout",mouseout);// mouseout is defined below.
            

        
        function mouseover(d){  // utility function to be called on mouseover.
            // filter for selected state. // line change happened
            var st = fData.filter(function(s){ return s.Time == d[0];})[0],
                nD = d3.keys(st.Operation).map(function(s){ return {type:s, Operation:st.Operation[s]};});
               
            // call update functions of pie-chart and legend.    
            pC.update(nD);
            leg.update(nD);
        }
        
        function mouseout(d){    // utility function to be called on mouseout.
            // reset the pie-chart and legend.    
            pC.update(tF);
            leg.update(tF);
        }
        
        // create function to update the bars. This will be used by pie-chart.
        hG.update = function(nD, color){
            // update the domain of the y-axis map to reflect change in frequencies.
            y.domain([0, d3.max(nD, function(d) { return d[1]; })]);
            
            // Attach the new data to the bars.
            var bars = hGsvg.selectAll(".bar").data(nD);
            
            // transition the height and color of rectangles.
            bars.select("rect").transition().duration(500)
                .attr("y", function(d) {return y(d[1]); })
                .attr("height", function(d) { return hGDim.h - y(d[1]); })
                .attr("fill", color);

            // transition the frequency labels location and change value.
            bars.select("text").transition().duration(500)
                .text(function(d){ return d3.format(",")(d[1])})
                .attr("y", function(d) {return y(d[1])-5; });            
        }        
        return hG;
    }
    
    // function to handle pieChart.
    function pieChart(pD){
        var pC ={},    pieDim ={w:250, h: 250};
        pieDim.r = Math.min(pieDim.w, pieDim.h) / 2;
                
        // create svg for pie chart.
        var piesvg = d3.select(id1).append("svg")
            .attr("width", pieDim.w).attr("height", pieDim.h).append("g")
            .attr("transform", "translate("+pieDim.w/2+","+pieDim.h/2+")");
        
        // create function to draw the arcs of the pie slices.
        var arc = d3.svg.arc().outerRadius(pieDim.r - 10).innerRadius(0);

        // create a function to compute the pie slice angles.
        var pie = d3.layout.pie().sort(null).value(function(d) { return d.Operation; });

        // Draw the pie slices.
        piesvg.selectAll("path").data(pie(pD)).enter().append("path").attr("d", arc)
            .each(function(d) { this._current = d; })
            .style("fill", function(d) { return segColor(d.data.type); })
            .on("mouseover",mouseover).on("mouseout",mouseout);

        // create function to update pie-chart. This will be used by histogram.
        pC.update = function(nD){
            piesvg.selectAll("path").data(pie(nD)).transition().duration(500)
                .attrTween("d", arcTween);
        }        
        // Utility function to be called on mouseover a pie slice.
        function mouseover(d){
            // call the update function of histogram with new data.
            hG.update(fData.map(function(v){ 
                return [v.Time,v.Operation[d.data.type]];}),segColor(d.data.type));
        }
        //Utility function to be called on mouseout a pie slice.
        function mouseout(d){
            // call the update function of histogram with all data.
            hG.update(fData.map(function(v){
                return [v.Time,v.total];}), barColor);
        }
        // Animating the pie-slice requiring a custom function which specifies
        // how the intermediate paths should be drawn.
        function arcTween(a) {
            var i = d3.interpolate(this._current, a);
            this._current = i(0);
            return function(t) { return arc(i(t));    };
        }    
        return pC;
    }
    
    // function to handle legend.
    function legend(lD){
        var leg = {};
            
        // create table for legend.
        var legend = d3.select(id1).append("table").attr("class","legend");
        
        // create one row per segment.
        var tr = legend.append("tbody").selectAll("tr").data(lD).enter().append("tr");
            
        // create the first column for each segment.
        tr.append("td").append("svg").attr("width", "16").attr("height", "16").append("rect")
            .attr("width", "16").attr("height", "16")
			.attr("fill",function(d){ return segColor(d.type); });
            
        // create the second column for each segment.
        tr.append("td").text(function(d){ return d.type;});

        // create the third column for each segment.
        tr.append("td").attr("class","legendFreq")
            .text(function(d){ return d3.format(",")(d.Operation);});

        // create the fourth column for each segment.
        tr.append("td").attr("class","legendPerc")
            .text(function(d){ return getLegend(d,lD);});

        // Utility function to be used to update the legend.
        leg.update = function(nD){
            // update the data attached to the row elements.
            var l = legend.select("tbody").selectAll("tr").data(nD);

            // update the frequencies.
            l.select(".legendFreq").text(function(d){ return d3.format(",")(d.Operation);});

            // update the percentage column.
            l.select(".legendPerc").text(function(d){ return getLegend(d,nD);});        
        }
        
        function getLegend(d,aD){ // Utility function to compute percentage.
            return d3.format("%")(d.Operation/d3.sum(aD.map(function(v){ return v.Operation; })));
        }

        return leg;
    }
    
    // calculate total frequency by segment for all state.
    var tF = ["target_clicked","scroll_down","scroll_up","select"].map(function(d){ 
        return {type:d, Operation: d3.sum(fData.map(function(t){ return t.Operation[d];}))}; 
    });    
    
    // calculate total frequency by state for all segment.
    var sF = fData.map(function(d){return [d.Time,d.total];});

    var hG = histoGram(sF), // create the histogram.
        pC = pieChart(tF), // create the pie-chart.
        leg= legend(tF);  // create the legend.
}

dashboard("#visualisation1","#visualisation2",Data);
//$("#visualisation3").jQCloud(words);



$("#visualisation3").jQCloud(words, {
      width: 900,
      height: 250,
      colors: ["#3fc080", "#33cccc", "#33adff", "#e6b800", "#cc0044","#ff8000","#e6004c","#ff4d4d", "#800000"],
      fontSize: {
        from: 0.05,
        to: 0.02
      }
    
    });
</script>

';
mysqli_close($server);

?>
