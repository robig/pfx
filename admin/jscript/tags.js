/*
 * jQuery jTagging plugin
 * Version 1.0.0  (10/01/2007)
 *
 * Copyright (c) 2007 Alcohol.Wang
 * Dual licensed under the MIT and GPL licenses.
 *
 * http://www.alcoholwang.cn/jquery/jTagging.htm
*/

(
	function($j)
	{
		$j.jTagging =
		{
			version : "1.0.0",
			defaults : 
			{
				normalStyle : { padding : "2px 1px 0 1px", textDecoration : "none", color : "#6665cb", backgroundColor : "" },
				selectedStyle : { padding : "2px 1px 0 1px", textDecoration : "none", color : "#fff", backgroundColor : "#6665cb"},
				normalHoverStyle : { padding : "2px 1px 0 1px", textDecoration : "none", color : "#fff", backgroundColor : "#6665cb"}
			}, 
			arrayRemove : function(array, value)
			{
				array = array || [];
				for(var o in array)
				{
					array[o] = $j.trim(array[o]);
					if (array[o] == value || array[o] == "")
					{
						array.splice(o, 1);
					}
				}
			},
			setClass : function(el, nc, hc)
			{
				$j(el).css(nc);
				$j(el).hover
				(
					function()
					{
						$j(el).css(hc);
					}
					,
					function()
					{
						$j(el).css(nc);
					}
				);
			}
		};
	
		$j.fn.jTagging = function(tags, seperator,normalStyle, selectedStyle, normalHoverStyle)
		{
			seperator = seperator || ",";
			normalStyle =normalStyle || $j.jTagging.defaults.normalStyle;
			selectedStyle =selectedStyle || $j.jTagging.defaults.selectedStyle;
			normalHoverStyle = normalHoverStyle || $j.jTagging.defaults.normalHoverStyle;
			tags = [tags];
		    return this.each
			(
				function()
				{
					var name = this.nodeName.toLowerCase();
					var type = this.type.toLowerCase();
					if  (name != "input" || type != "text"  && name != "textarea")
					{
						throw "Element must be \"input:text\" or \"textarea\"";
					}
					
					var input = this;
					
					$j.each
					(
						["keydown", "keyup"]
						,
						function(i, n)
						{
							$j(input).bind
							(
								n
								,
								function()
								{
									$j.each
									(
										tags, function(i, n)
										{
											$j.each
											(
												n, function (j, o)
												{
													 $j("a", o).each
													 (
														function(k)
														{
															var value = $j(input).val().split(seperator);
															$j.jTagging.arrayRemove(value);
															if ($j(value).index($j(this).text()) >= 0)
															{
																$j.jTagging.setClass(this, selectedStyle, normalHoverStyle);
															}
															else
															{
																$j.jTagging.setClass(this, normalStyle, normalHoverStyle);
															}
														}
													 );
												}
											);
										}
									);
								}
							);
						}
					);
					
					$j.each
					(
						tags, function(i, n)
						{
							$j.each
							(
								n, function (j, o)
								{
									 $j("a", o).each
									 (
										function(k)
										{
											$j(this).removeClass();
											$j(this).attr("href", "#");
											$j(this).click
											(
												function()
												{
													var value = $j(input).val().split(seperator);
													$j.jTagging.arrayRemove(value);
													if ($j(value).index($j(this).text()) >= 0)
													{
														$j.jTagging.arrayRemove(value, $j(this).text());
														$j(input).val(value.join(seperator));
														$j.jTagging.setClass(this, normalStyle, normalHoverStyle);
													}
													else
													{
														value.push($j(this).text());
														$j(input).val(value.join(seperator));
														$j.jTagging.setClass(this, selectedStyle, normalHoverStyle);
													}
													this.blur();
													return false;
												}
											);

											var value = $j(input).val().split(seperator);
											$j.jTagging.arrayRemove(value);
											if ($j(value).index($j(this).text()) >= 0)
											{
												$j.jTagging.setClass(this, selectedStyle, normalHoverStyle);
											}
											else
											{
												$j.jTagging.setClass(this,normalStyle, normalHoverStyle);
											}
										}
									);
								}
							);
						}
					);
				}
			);
		}
	}
)
(jQuery);