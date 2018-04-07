<?php

$gallery_code .= '

<script type="text/javascript">
/* <![CDATA[ */

// Variable declarations
var x = 0,	// y coordinate
	y = 0; 	// x coordinate
// ends varaiable declarations

// check if browser doesnt supports document.all
// capute mouse movement if document.all is not supported.
if( !document.all )
{
	document.captureEvents(Event.MOUSEMOVE)
} //-- ends if statement

// regester event listner for mouse move
// with function setCoordinates
window.document.onmousemove = setCoordinates;

// function to obtain mouse coordinates
function setCoordinates( e )
{
	// if browser supports document.all such as IE or opera.
	if( document.all )
	{
		x = window.event.clientX;
		y = window.event.clientY;
	}
	else
	{
		x = e.pageX;
		y = e.pageY;
	}
} // ends function setCoordinates.

/* Function to display the tooltip
	@param: element => reference to the caller
	@param: title => tooltip title...optional
	@param: msg => tooltip message or body
*/
function toolTip( element, title, msg )
{
	// create a div element
	var el = window.document.createElement( "div" );

	// check if title has been supplied.
	if( title.length > 0 )
	{
		var head = window.document.createElement( "h1" );
		head.innerHTML = title;
		el.appendChild( head );
	} //-- ends if statement

	// create a paragraph element
	var para = window.document.createElement( "P" );
	para.innerHTML = msg;
	el.appendChild( para );
	// style div element
	el.style.left = x + 15 + "px";
	el.style.top = y + 10 + "px";
	el.style.display = "block";

	// check if browser if IE or opera and append class name
	if ( document.all ) el.className = "tips";
	else el.setAttribute( "class", "tips" );

	for( var i=0; i<element.parentNode.childNodes.length; i++ )
	{
		if( element.parentNode.childNodes[ i ].className == "tips" )
		{
			element.parentNode.removeChild( element.parentNode.childNodes[ i ] );
		}
	}

	element.parentNode.appendChild( el );

} // ends function toolTip

function removeTip( element )
{
	for( var i = 0; i < element.parentNode.childNodes.length; i++  )
	{
		if( element.parentNode.childNodes[ i ].className == "tips" )
			element.parentNode.removeChild( element.parentNode.childNodes[ i ] );
	} // ends for
} // ends removeTip

/* ]]> */
</script>

';


?>