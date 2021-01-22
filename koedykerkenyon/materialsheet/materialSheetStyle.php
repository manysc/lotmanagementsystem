<style>
#companyName {
	width : 60%;
	height : 6em;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
	font-size: 20px;
	font-family: sans-serif;
	resize: none;
}

#poNumber {
	width : 30%;
	height : 6em;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
	font-size: 20px;
	font-family: sans-serif;
	resize: none;
}

#shipToLabel, #billToLabel {
	width : 13%;
	height : 4em;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
	font-size: 20px;
   font-family: sans-serif;
   font-weight: bold;
   resize: none;
}

#shipToValue, #billToValue {
	width :30%;
	height : 4em;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
	font-size: 20px;
   font-family: sans-serif;
   resize: none;
}

#vendor, #shipTo, #billTo {
	width :30%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
}

#materials, #materialTotals {
	width :45%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
}

#receivedBy {
	width : 50%;
	height : 6em;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
	font-size: 20px;
	font-family: sans-serif;
	resize: none;
}

#materials {
	width :100%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color: transparent;
}

.materialsTable {
   border-collapse:collapse;
   text-align: center;
}

.materialsTable th {
   background-color:#0CA;
   color:Black;
   font-size:25px;
   font-weight:bold;
   padding:5px;
   border: none;
}

.materialsTable td {
   padding:5px;
   border: none;
   font-size:23px;
   font-weight:normal;
}

.combobox {
	font-size: 23px;
}

.mediumRadioButton {
   height: 23px;
   width: 23px;
   vertical-align: middle;
}

/* class applies to select element itself, not a wrapper element */
.vendors-select {
  display: block;
  font-size: 23px;
  font-family: sans-serif;
  font-weight: 700;
  color: #444;
  line-height: 1.3;
  padding: .6em 1.4em .5em .8em;
  width: 50%;
  max-width: 50%; /* useful when width is set to anything other than 100% */
  box-sizing: border-box;
  margin: 0;
  border: 1px solid #aaa;
  box-shadow: 0 1px 0 1px rgba(0,0,0,.04);
  border-radius: .5em;
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  background-color: #EEE;
  background-image: url('../pictures/combobox_arrow.svg'),
    linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%);
  background-repeat: no-repeat, repeat;
  /* arrow icon position (1em from the right, 50% vertical) , then gradient position*/
  background-position: right .7em top 50%, 0 0;
  /* icon size, then gradient */
  background-size: .65em auto, 100%;
}

/* Hover style */
.vendors-select:hover {
  border-color: #888;
}

/* Focus style */
.vendors-select:focus {
  border-color: #aaa;
  /* It'd be nice to use -webkit-focus-ring-color here but it doesn't work on box-shadow */
  box-shadow: 0 0 1px 3px rgba(59, 153, 252, .7);
  box-shadow: 0 0 0 3px -moz-mac-focusring;
  color: #222;
  outline: none;
}

/* Set options to normal weight */
.vendors-select option {
  font-weight:normal;
}

</style>