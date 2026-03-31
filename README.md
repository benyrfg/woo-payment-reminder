# Woo Payment Reminder

Automatický WordPress plugin pro odesílání upomínkových emailů zákazníkům s nezaplatenými objednávkami ve WooCommerce.

## ✨ Funkce

- **Automatické upomínky**: Odesílá emaily zákazníkům s nezaplatenými objednávkami
- **Dva typy upomínek**: První a druhá upomínka s různými časovými nastaveními
- **Konfigurovatelné časování**: Nastavitelný počet hodin před odesláním každé upomínky
- **Integrace s WooCommerce**: Používá nativní WooCommerce email systém
- **Vícejazyčná podpora**: Angličtina a čeština (rozšiřitelné)
- **Plain text & HTML**: Podpora obou formátů emailů
- **Historie objednávek**: Automatické zaznamenávání odeslaných upomínek do poznámek objednávky

## 📋 Požadavky

- WordPress 5.0+
- WooCommerce 4.0+
- PHP 7.0+

## 🔧 Instalace

1. Stáhněte ZIP soubor pluginu
2. Přejděte do WordPress adminu → Plugins → Add New → Upload Plugin
3. Nahrajte ZIP soubor a aktivujte plugin
4. Nastavte plugin v WooCommerce → Settings → Emails → Payment Reminder

## ⚙️ Nastavení

Po aktivaci pluginu přejděte do:

**WordPress Admin → WooCommerce → Settings → Emails → Payment Reminder**

### Dostupná nastavení:

| Nastavení | Výchozí hodnota | Popis |
|-----------|----------------|--------|
| Enable/Disable | Yes | Zapne/vypne automatické odesílání upomínek |
| Hours before first reminder | 168 (7 dní) | Počet hodin od vytvoření objednávky do odeslání první upomínky |
| Enable second reminder | Yes | Zapne/vypne druhou upomínku |
| Hours before second reminder | 240 (10 dní) | Počet hodin od vytvoření objednávky do odeslání druhé upomínky |
| Subject | (výchozí) | Předmět emailu (ponechte prázdné pro výchozí) |
| Email Heading | (výchozí) | Nadpis emailu (ponechte prázdné pro výchozí) |
| Email type | HTML | Formát emailu (Plain text, HTML, or Multipart) |

## 🔄 Jak to funguje

1. **Cron job**: Plugin vytvoří cron job, který se spouští každou hodinu
2. **Kontrola objednávek**: Cron hledá objednávky se statusem "pending" nebo "on-hold"
3. **Odesílání upomínek**: Pokud objednávka splňuje časová kritéria, odešle se upomínka
4. **Ochrana proti spamu**: Mezi jednotlivými upomínkami musí uběhnout minimálně 3 dny
5. **Záznam**: Každá odeslaná upomínka se zaznamená do poznámek objednávky

## 📧 Šablony emailů

Plugin obsahuje dva typy šablon:

- **HTML šablona**: [`templates/emails/payment-reminder.php`](templates/emails/payment-reminder.php)
- **Plain text šablona**: [`templates/emails/plain/payment-reminder.php`](templates/emails/plain/payment-reminder.php)

Šablony lze přepsat ve vaší child theme složce:
```
your-theme/woocommerce/emails/payment-reminder.php
```

## 🌍 Lokalizace

Plugin podporuje překlady a obsahuje:

- 🇬🇧 Angličtina (en_US)
- 🇨🇿 Čeština (cs_CZ)

Překladové soubory se nacházejí v [`languages/`](languages/) složce.

### Jak přidat nový překlad:

1. Použijte nástroj jako Poedit
2. Otevřete [`languages/woo-payment-reminder.pot`](languages/woo-payment-reminder.pot)
3. Vytvořte nový `.po` a `.mo` soubor pro váš jazyk
4. Uložte do `languages/` složky

## 🔍 Bezpečnostní kontrola

Plugin prošel bezpečnostním auditem a obsahuje:

✅ **ABSPSECURE protection** - ochrana proti přímému přístupu
✅ **Input sanitization** - sanitizace uživatelských vstupů
✅ **XSS protection** - escapování výstupu v šablonách
✅ **SQL injection protection** - používá WooCommerce ORM
✅ **WordPress best practices** - řádné hooks a filters
✅ **Cron management** - správné activation/deactivation hooks

## 🐛 Řešení problémů

### Emaily nechodí

1. Zkontrolujte, že je plugin zapnutý v nastavení WooCommerce Emails
2. Zkontrolujte WooCommerce System Status pro případné email problémy
3. Zkontrolujte cron joby: použitím pluginu jako "WP Crontrol"
4. Zkontrolujte error log: `/wp-content/debug.log`

### Cron se nespouští

Zkontrolujte, že WordPress cron funguje:
```bash
# Zkontrolujte wp-cron.php
wp cron event list
```


## 📊 Struktura souborů

```
woo-payment-reminder-main/
├── woo-payment-reminder.php          # Hlavní soubor pluginu
├── classes/
│   └── class-wc-email-payment-reminder.php  # Email třída
├── templates/
│   └── emails/
│       ├── payment-reminder.php              # HTML šablona
│       └── plain/
│           └── payment-reminder.php          # Plain text šablona
├── languages/
│   ├── woo-payment-reminder.pot              # Šablona pro překladatele
│   ├── woo-payment-reminder-en_US.po         # Anglický překlad
│   ├── woo-payment-reminder-en_US.mo
│   ├── woo-payment-reminder-cs_CZ.po         # Český překlad
│   └── woo-payment-reminder-cs_CZ.mo
├── README.md                         # Tento soubor
├── LICENSE                           # GPLv3 license
└── .gitignore                        # Git ignore soubor
```

## 🛠️ Vývojářská dokumentace

### Hooks a Filters

**Action hook pro odeslání emailu:**
```php
do_action( 'send_payment_reminder_email', $order_id );
```

**Vlastní processing před odesláním:**
```php
add_action( 'send_payment_reminder_email', 'my_custom_function', 5, 1 );
function my_custom_function( $order_id ) {
    // Váš kód
}
```

### Meta data

Plugin ukládá následující meta data k objednávkám:

- `_payment_reminder_sent`: Timestamp poslední odeslané upomínky

## 📝 Changelog

### 1.0.0 (2026-03-31)
- 🎉 První veřejná verze
- ✨ Podpora pro první a druhou upomínku
- 🌍 Anglická a česká lokalizace
- 📧 HTML a plain text email šablony
- ⚙️ Konfigurovatelné časování

## 📜 Licence

Tento plugin je licencován pod [GPLv3](LICENSE).

## 👨‍💻 Autor
Plugin byl upraven pomocí AI. Původní plugin:
- GitHub: [@nobodyguy](https://github.com/nobodyguy)

## 🤝 Přispěvování

Otevřte issue nebo pull request.

## 📞 Podpora

Pokud narazíte na problémy, prosím:
1. Zkontrolujte tento README soubor
2. Vytvořte issue na GitHub repository
3. Kontaktujte autora

---

**Poslední aktualizace**: 31. března 2026
**Verze**: 1.0.0