# Domain Module for WHMCS

Changelog:
- ⚠ Enable Premium domain support explicitly (see Enable premium domains) ⚠
- Started refactoring code (supports PHP 7.X)
- Includes addon to find miss-invoiced domains
 
Features
- Supports registering and transferring domains
- Updates (contact changes, nameserver changes, toggle lock)
- Whois Privacy Protection
- DNS management via OpenProvider module
- Whois lookup service: more reliable than the default whois servers in WHMCS
- Domain status extended Synchronisation (more reliable than WHMCS's synchronisation)
- Domain status synchronisation reports


# Installation
1. Download the module.
2. Upload modules/registrars/openprovider to /modules/registrars in WHMCS.  
    2.1 [optional](#addon) Upload modules/addons/openprovider to /modules/addons in WHMCS.
3. Navigate to Setup -> Products/Services -> Domain Registrars and activate OpenProvider. Use `https://api.openprovider.eu` as the API url. DNS templates are loaded once valid login details are saved. Use the table below as a reference.
4. Click on Save.
5. Select the DNS template (if needed):

![alt text](http://pic001.filehostserver.eu/116673.png "OpenProvider registrar configuration screen")

6. Click on Save.
7. Navigate to Setup -> Products/Services -> Domain Pricing and select OpenProvider as registrar for every TLD.
8. install the Cron job: See below
9. Add to `resources/domains/additionalfields.php` the following:
```
<?php
/**
* OpenProvider only overrides additional fields that are configured in WHMCS Domain Pricing with OpenProvider as registrar.
* Put this code above the other fields. Don't override additional fields manually for OpenProvider: we maintain this for you.
*/
$additionaldomainfields = openprovider_additional_fields();
```
10. Navigate to Setup -> Addon Modules and activate OpenProvider.
11. Click on Configure and grant access to the specified roles.


Option details:

| Variable | Value |
| ----- | ----- | 
| OpenProvider URL	 | Should be `https://api.openprovider.eu` for production or `https://api.cte.openprovider.eu` for test environments. |
|Support Premium Domains| Allows you to confirm that you allow the module to register domains with a premium price|
| Username | Your OpenProvider username |
| Password | Your password or password hash |
| Synchronize due-date with offset?	| Check if you want to offset the next due dates. By doing so, your client has to pay more in advance |
| Due-date offset | The offset (by default 3) |
| Update interval | The minimum delay in hours between every domain status update (by default 2). WARNING: lowering this can overload your and OpenProvider's system! |
| Domain process limit | The maximum amount of domains to process in each cron run. |
| Send empty activity reports | Send a report even when nothing has been updated in a cron run. |
| DNS template | Select the DNS template you prefer. NOTE: only shows up after the correct login details have been saved!|

# Openprovider Domain Sync
## Setting up cron task
In order for the Openprovider plugin for WHMCS to function properly, a cron job needs to be scheduled for the script `<your WHMCS root Directory>/modules/registrars/openprovider/cron/DomainSync.php`.

This task keeps domain statuses, expiration dates, WPP, and auto renew settings synchronized between Openprovider and WHMCS. Domain statuses and expiration dates are led by domain settings on Openprovider, and auto renew settings are led by WHMCS settings. This script updates by default 200 domains per execution (the value can be changed in the module configuration), and should be run so that all domains are synchronized within 6 hours. For example, if you have 7000 domains in WHMCS with Openprovider, the cron job needs to be run 7000 / 200 = 35 times every six hours, i.e. at least once every 10 minutes. (360 minutes / 35 syncs = at most 10.2 minutes/sync)

Note that WHMCS has a different script, cron.php, which it uses to perform many of its functions, including generating invoices, and syncing domain statuses with other registries. The DomainSync script from Openprovider is in addition to the WHMCs cron job, and needs to be scheduled separately. As the impementation of WHMCS to synchronize the domain status is limited, a separate cron is required.

## Domain Sync Email Update
The DomainSync task from the Openprovider domain module sends an email report every time a domain object in WHMCS or Openprovider is modified. If "Send empty activity reports is selected" in the module configuration window, then the update will be sent every time the task runs, even if no domains have been modified.

To configure, in the WHMCS admin area, navigate to Setup > Staff Management > Administrator Roles, and select the group of administrators which you want to receive the emails. Ensure that "system emails" is selected in this group:

![alt text](http://pic001.filehostserver.eu/116672.png "Domain Sync Email update")

## Updated settings
The domain sync summarizes the following changes to the WHMCS domain objects:

### Openprovider is leading, WHMCS settings are updated:
###### 1. Expiration date
1. This sets the WHMCS expiration date equal to the Openprovider expiration date. Note that for certain TLDs there is an offset between the Openprovider expiration date and the Registry expiration date, but the Openprovider expiration date is the date for which payment is due for renewals.

###### 2. Due date
1. This is the date for which WHMCS invoices are due, and is equal to the WHMCS expiration date offset by the value selected in "Next due date offset" in the Openprovider domain module settings window (if “synchronize due date with offset?” is checked). For example, if the expiration date is "20 May", and the offset is set as "10" then the next due date will be set as "10 May"
2. If “synchronize due date with offset?” is not checked, then due date will be set equal to expiration date.

###### 3. Domain status
1. This lists domains which had their statuses changed in WHMCs to reflect their status in Openprovider. Domains statuses are mapped according to the following:
2. 'ACT' => 'Active', // ACT The domain name is active
3. 'DEL' => 'Expired', // DEL The domain name has been deleted, but may still be restored.
4. 'PEN' => 'Pending', // PEN The domain name request is pending further information before the process can continue.
5. 'REQ' => 'Pending', // REQ The domain name request has been placed, but not yet finished.
6. 'RRQ' => 'Pending', // RRQ The domain name restore has been requested, but not yet completed.
7. 'SCH' => 'Pending', // SCH The domain name is scheduled for transfer in the future.
8. If a domain has the status FAI in Openprovider, because of a transfer failure, then an error will be logged in the admin area as " The domain name request has failed "

### WHMCS is leading, Openprovider settings are updated:
Domain Auto-renew and ID protection settings are sent to Openprovider immediately if any changes are made from WHMCS. If something is changed in Openprovider, then the DomainSync task will ensure that auto-renew and ID settings stay synchronized.

###### 1. Domain Auto renew
1. If a domain is set to auto renew "on" in WHMCs, then the corresponding domain object in Openprovider is set to "global default." If the WHMCS domain object is set to auto-renew "off" then the corresponding domain object in Openprovider is also set to off.

###### 2. Domain Whois Privacy Protection (ID protection)
1. ID protection settings on a domain are mapped to Openprovider whois privacy protection settings.

Any changes made to a domain by the DomainSync task will be listed in an email sent to the administrators in the group you have specified:
![alt text](http://pic001.filehostserver.eu/116671.png "Domain Sync")

# TLD Management and Check domain availability
Navigate to the "config domains" page, located at `<your WHMCS domain>/admin/configdomains.php` ​or through the menu at Setup -> Products/Services -> Domain Pricing​ where you will see (1) a place to select the lookup provider details, (2) a place to choose a TLD, (3) a dropdown to choose which domain provider to choose as the default registrar for the domain, and (4) offer ID protection, which will synchronize ID protection orders with whois privacy protection WPP.

![alt text](http://pic001.filehostserver.eu/116662.png "TLD Management and Check domain availability")

## Select a TLD and Auto Registration
1. Insert the desired TLD (2) and from the Auto Registration dropdown (3) select Openprovider
2. Click Save, and an option "open pricing" will appear
![alt text](http://pic001.filehostserver.eu/116663.png "Select a TLD and Auto Registration")
3. Click "open pricing" and define prices for register, transfer, and renewal in the popup box which appears
4. That's it! your WHMCS installation is ready to offer domains for registration for your clients

## Select a TLD and Auto Registration
![alt text](http://pic001.filehostserver.eu/116664.png "Select a TLD and Auto Registration")
By selecting ID protection for a given TLD, when clients purchase it on a domain, whois privacy protection (WPP) will be automatically activated in Openprovider. Note that there will be a charge from Openprovider for this service. If clients deactivate it in WHMCS, it will also be deactivated in Openprovider.

# Enable premium domains

⚠️Make sure that the currency that you are using to pay OpenProvider is configured in Setup -> Payments -> Currencies (and click on Update Exchange Rate). Otherwise WHMCS will not use the premium fee correctly, meaning that your client pays the regular fee.⚠️

1. Navigate to Setup -> Products / Services -> Domain Registrars
2. Configure OpenProvider
3. Enable "Support premium domains"
4. Save Changes.


# Choose Lookup Provider
1. Under lookup provider (1) choose "change" and from the popup menu select "Openprovider"
2. The lookup provider should be displayed as such:
![alt text](http://pic001.filehostserver.eu/116665.png "Choose Lookup Provider")
3. All domain availability checks by your customers will now be performed using the Openprovider API.

# Admin Area
## Accepting Orders
Once a domain has been ordered by a client, the order appears as pending in the admin area `<your WHMCS domain>/admin/orders.php` ​or through the menu at Orders>Pending Orders​. WHMCS logic is that all orders must be approved by an administrator before being ordered at the registrar. Below the client has ordered and paid for the domain "register-a-new-domain.nl" but it hasn't yet been approved.
![alt text](http://pic001.filehostserver.eu/116666.png "Accepting Orders")

Note from the previous section that Openprovider has been set as the registrar for auto registration for ".nl" domains. When you click accept order, the order is automatically placed at Openprovider. If there are other registrar options, there is a dropdown menu that can be chosen.

# Managing Domains
Once a domain has been registered and activated, the domain can be managed from the admin area. You can navigate to Clients>Domain Registrations ​and select the desired domain. From here, there are options to send commands to Openprovider, including register, transfer, renew, change contact details, edit name servers, toggle registrar lock, and delete the domain.

![alt text](http://pic001.filehostserver.eu/116667.png "Managing Domains")

# Renewing Domains
If "automatic renew on payment" is selected (which can be found in WHMCS admin area, setup > general settings > domains) ​and Openprovider is set as the auto registration provider, then the module will automatically register or renew the domain in Openprovider via API as soon as a client pays for domain renewal or registration.

When a domain expires in Openprovider, depending on the TLD, it can be put into 'soft quarantine.' When a domain is in soft quarantine, the domain can be restored for the normal renewal fee, but restoration needs to be requested with the "restore domain request" API command. The Openprovider domain module automatically detects when the domain is in soft quarantine, and make the appropriate API to request a restore. The module will not request renewal if the domain has already passed into "hard quarantine" and can only be restored for an additional fee.

# Using Custom DNS Templates
Once you've created a custom DNS template in the Openprovider control panel (DNS management > Manage DNS templates), and selected it in the module configuration window, any domain created with the Openprovider name servers will have a DNS zone automatically created on the Openprovider name servers.

# Auto Renew Configurations
WHMCS suggests that you have auto renew to "off" in the Openprovider system. This greatly reduces the chance that a domain will be "double renewed" in your account, which is possible if a domain has a once from auto renew, and again when the customer pays. Please thoroughly read the WHMCS documentation before deciding on the business logic you will use concerning auto-renew settings.

# Addon 
The (#addon) calculates if there are domains that have a different invoice count than expected. It does this by taking the registration date and the expiry date. Based on this the invoice count per year is calculated and 0.1 is deducted to overcome small differences. The result are domains that may have not been invoiced correctly due to limitations from within WHMCS. The addon also works with other providers but does require the OpenProvider registrar module to be uploaded and activated. You are not required to use the addon and OpenProvider does not take any responsibility in the correctness of the addon.

## How to take action
1. Click on Actions -> Invoices. This will show all technical invoices. Use the search bar on the invoice index page for manually created invoices.
2. Create invoice: this page has an estimated amount that is not billed via WHMCS.
3. Create ticket: contact the client about this.
4. Sync Invoice count: if everything is allright, this will synchronise the actual invoice count. Should there be a difference after a few years, the domain will show up again.

## Limitations
The addon assumes that every domain registration is only for one year and renewed for one year.

# Troubleshooting
If there are any issues with connection with Openprovider, or for some reason API commands are not working, the first troubleshooting step should be to look at the API logs. Navigate to Utilities>Logs>Module Logs ​or <your WHMCS domain>/admin/systemmodulelog.php​ and you can find the raw API commands being sent and received by your WHMCS modules. The responses should contain some information about how the problem can be solved.

![alt text](http://pic001.filehostserver.eu/116668.png "Troubleshooting")

Please contact our support staff with any questions
Support@Openprovider.nl
