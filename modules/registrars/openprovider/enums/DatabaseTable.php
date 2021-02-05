<?php

namespace OpenProvider\WhmcsRegistrar\enums;

class DatabaseTable
{
    // Custom tables
    const ClientTags = 'tblclienttags';

    const MappingInternalExternalContacts = 'tblmappinginternalexternalcontacts';

    const ImportedInvoicesMap = 'tblmappingimportedinvoices';

    const Invoices = 'tblinvoices';

    const InvoiceItems = 'tblinvoiceitems';

    const InvoiceData = 'tblinvoicedata';
  
      // Default tables
    const Orders = 'tblorders';

    const UsersClients = 'tblusers_clients';

    const Domains = 'tbldomains';

    const Products = 'tblproducts';

    const Currencies = 'tblcurrencies';
}