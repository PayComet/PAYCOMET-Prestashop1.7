{**  @author     PAYCOMET <info@paycomet.com>
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author     PAYCOMET <info@paycomet.com>
*  @copyright  2015 PAYTPV ON LINE S.L.
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<section id="order-paycomet" class="box">
	{$result_txt}
	<div style="display: {$display}">
	{l s='Payment information of' mod='paytpv'}
	<img src="{$this_path|escape:'htmlall':'UTF-8':FALSE}views/img/apms/multibanco.svg" width="100">
	<p>{l s='Entity' mod='paytpv'}: {$mbentity}</p>
	<p>{l s='Reference' mod='paytpv'}: {$mbreference}</p>
	</div>
</section>
