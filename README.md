# magento2-pdf-invoice-image

#Features
<ul>
<li>Add Image for Order Item into Invoice PDF </li>
</ul>

<br/>
<h2> Mannual Installation Instructions</h2>
go to Magento2Project root dir 
create following Directory Structure :<br/>
<strong>/Magento2Project/app/code/EmizenTech/InvoicePdfimage</strong>
you can also create by following command:
<pre>
cd /Magento2Project
mkdir app/code/EmizenTech
mkdir app/code/EmizenTech/InvoicePdfimage
</pre>

now upload module files in <strong>/Magento2Project/app/code/EmizenTech/InvoicePdfimage</strong>

<h3> Enable Emizentech/InvoicePdfimage Module</h3>
to Enable this module you need to follow these steps:

<ul>
<li>
<strong>Enable the Module</strong>
<pre>bin/magento module:enable EmizenTech_InvoicePdfimage</pre></li>
<li>
<strong>Run Upgrade Setup</strong>
<pre>bin/magento setup:upgrade</pre></li>
</ul>
