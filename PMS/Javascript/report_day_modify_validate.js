// JavaScript Document
//驗證欄位
function validate()
{
	var i=0, flag=true;
	while(i<report_day.status.length && flag)
	{
		if(report_day.status[i].checked)
		{
			flag = false;
		}
		i++;
	}
	
	if(document.report_day.program.value == "")
	{
		alert("Please choose program");
	}
	else if(document.report_day.job_type.value == "-1")
	{
		alert("Please choose Type");
	}
	else if(flag)
	{
		alert("Please choose Status");
		report_day.status.select();
		window.event.returnValue = false;
	}
	else if(document.report_day.remark.value == "")
	{
		alert("Please say something");
	}
	else if(document.report_day.cost.value == "")
	{
		alert("Please input cost time");
	}	
	else
		report_day.submit();
}