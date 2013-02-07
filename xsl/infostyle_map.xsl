<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:template match="/">
    	<xsl:apply-templates select="location"/>
  	</xsl:template>

	<xsl:template match="location">
    	<div style="padding-right: 8px; padding-left: 3px; margin-top: 2px">
    		<xsl:apply-templates select="info"/>
    	</div>
  	</xsl:template>

  	<xsl:template match="info">
    	
    	<xsl:variable name="page" select="../@arg0"/>    	

    	<div style="padding: 2px; width: 250px">
    		<font style="font-weight: bold;">Event: </font> <xsl:value-of select="name"/>
    	</div>    	
		<div style="padding: 2px; width: 250px">
    		<font style="font-weight: bold;">Area: </font><xsl:value-of select="area"/>
    	</div>    	    	    	
		<div style="padding: 2px; width: 250px">
    		<font style="font-weight: bold;">Venue: </font><xsl:value-of select="venue"/>
    	</div>    	    	    	
    	<div style="padding: 2px; width: 250px">
    		<xsl:value-of select="description"/>
    	</div>
    	<div style="padding: 2px; width: 250px">
			<a target="_blank" style="background:white; color:#3300FF; text-decoration:underline;">
			   	<xsl:attribute name="href">
			   		<xsl:value-of select="url"/>
			   	</xsl:attribute>
			   	Click here for details
    		</a>
    	</div>    	
	</xsl:template>

</xsl:stylesheet>