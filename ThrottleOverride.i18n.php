<?php

/**
 * Internationalisation file for extension Throttle Override
 *
 * @file
 * @ingroup Extensions
 * @license GNU General Public Licence 3.0 or later
 */

$messages = array();

/**
 * English
 * @author Tyler Romeo <tylerromeo@gmail.com>
 */
$messages['en'] = array(
	'overridethrottle' => 'Override the account creation throttle',
	'throttleoverridelist' => 'List of throttle overrides',
	'throttleoverride-desc' => 'Allows overriding of IP address throttles',
	'throttleoverride-legend' => 'Exemption information',
	'throttleoverride-text' => 'Enter the IP address or range you want to exempt from certain throttles, and how long the exemption should last for.
An optional reason can be given for the logs.',
	'throttleoverride-ipaddress' => 'IP address or range',
	'throttleoverride-success' => 'The exemption was applied.',
	'throttleoverride-types' => 'Throttle types:',
	'throttleoverride-types-all' => 'All types',
	'throttleoverride-types-actcreate' => 'Account creation',
	'throttleoverride-types-edit' => 'Page edits',
	'throttleoverride-types-move' => 'Page moves',
	'throttleoverride-types-mailpassword' => 'Password recovery emails',
	'throttleoverride-types-emailuser' => 'User emails',
	'throttleoverride-list-throttletype' => 'Throttle type:',
	'throttleoverride-list-legend' => 'Exemption filtering',
	'throttleoverride-list-rangestart' => 'Start of IP range',
	'throttleoverride-list-rangeend' => 'End of IP range',
	'throttleoverride-list-expiry' => 'Expiry',
	'throttleoverride-list-type' => 'Allowed actions',
	'throttleoverride-list-reason' => 'Reason',
	'throttleoverride-list-search' => 'Search',
	'throttleoverride-list-noresults' => 'The throttle override list is empty.',
	'throttleoverride-validation-ipinvalid' => 'Invalid IP or IP range.',
	'throttleoverride-validation-rangedisabled' => 'The ability to create throttle exemptions on ranges of IP addresses is disabled.',
	'throttleoverride-validation-rangetoolarge' => 'Throttle exemptions over an IP range larger than $1 are not allowed.',
);

/** Message documentation (Message documentation)
 * @author Shirayuki
 */
$messages['qqq'] = array(
	'overridethrottle' => '{{doc-special|OverrideThrottle}}',
	'throttleoverridelist' => '{{doc-special|ThrottleOverrideList}}',
	'throttleoverride-desc' => '{{desc|name=Throttle Override|url=http://www.mediawiki.org/wiki/Extension:ThrottleOverride}}',
	'throttleoverride-legend' => 'Label for the legend on [[Special:OverrideThrottle]]',
	'throttleoverride-text' => 'Intro text on [[Special:OverrideThrottle]]',
	'throttleoverride-ipaddress' => 'Label for the IP address field on [[Special:OverrideThrottle]]',
	'throttleoverride-success' => 'Text displayed after a successful submission on [[Special:OverrideThrottle]]',
	'throttleoverride-types' => 'Label for the types of throttles that can be overridden.
{{Identical|Throttle type}}',
	'throttleoverride-types-all' => 'Label for the throttle type representing all types (used in [[Special:ThrottleOverrideList]]).
{{Identical|All types}}',
	'throttleoverride-types-actcreate' => 'Label for the throttle type for account creations',
	'throttleoverride-types-edit' => 'Label for the throttle type for page edits',
	'throttleoverride-types-move' => 'Label for the throttle type for page moves',
	'throttleoverride-types-mailpassword' => 'Label for the throttle type for password recovery requests',
	'throttleoverride-types-emailuser' => 'Label for the throttle type for user emails',
	'throttleoverride-list-throttletype' => 'Label for the throttle type on [[Special:ThrottleOverrideList]].
{{Identical|Throttle type}}',
	'throttleoverride-list-legend' => 'Fieldset legend on [[Special:ThrottleOverrideList]]',
	'throttleoverride-list-rangestart' => 'Table header in the throttle override list for the starting IP address of a throttle override',
	'throttleoverride-list-rangeend' => 'Table header in the throttle override list for the ending IP address of a throttle override',
	'throttleoverride-list-expiry' => 'Table header in the throttle override list for the expiry date and time of a throttle override.
{{Identical|Expiry}}',
	'throttleoverride-list-type' => 'Table header in the throttle override list for the actions allowed by a throttle override',
	'throttleoverride-list-reason' => 'Table header in the throttle override list for the reason of a throttle override.
{{Identical|Reason}}',
	'throttleoverride-list-search' => 'Label for the sumbit button on [[Special:ThrottleOverrideList]].
{{Identical|Search}}',
	'throttleoverride-list-noresults' => 'Message displayed on [[Special:ThrottleOverrideList]] when the pager returns no results',
	'throttleoverride-validation-ipinvalid' => 'Validation error displayed on [[Special:ThrottleOverride]] when an invalid IP address or IP range is given.',
	'throttleoverride-validation-rangedisabled' => 'Validation error displayed on [[Special:ThrottleOverride]] when a user tries to give an IP range to override for, but all range exemptions are disabled.',
	'throttleoverride-validation-rangetoolarge' => 'Validation error displayed on [[Special:ThrottleOverride]] when the IP range provided exceeds the configured allowable size.

Parameters:
* $1 - The CIDR limit as configured',
);

/** Breton (brezhoneg)
 * @author Y-M D
 */
$messages['br'] = array(
	'throttleoverride-types-actcreate' => 'Krouiñ kontoù',
	'throttleoverride-types-emailuser' => 'Posteloù implijer',
);

/** Chechen (нохчийн)
 * @author Умар
 */
$messages['ce'] = array(
	'throttleoverride-list-search' => 'Лаха',
);

/** Church Slavic (словѣньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 * @author ОйЛ
 */
$messages['cu'] = array(
	'throttleoverride-list-search' => 'ищи',
);

/** German (Deutsch)
 * @author Metalhead64
 */
$messages['de'] = array(
	'overridethrottle' => 'Die Kontenerstellungsbeschränkung umgehen',
	'throttleoverridelist' => 'Liste von Beschränkungsumgehungen',
	'throttleoverride-desc' => 'Ermöglicht das Umgehen von IP-Adressbeschränkungen',
	'throttleoverride-legend' => 'Ausnahmeinformationen',
	'throttleoverride-text' => 'Gib die IP-Adresse oder den Adressbereich an, die du von bestimmten Beschränkungen ausnehmen willst und wie lange die Ausnahme gelten soll.
Für die Logbücher kann eine optionale Begründung angegeben werden.',
	'throttleoverride-ipaddress' => 'IP-Adresse oder Adressenbereich',
	'throttleoverride-success' => 'Die Ausnahme wurde angewandt.',
	'throttleoverride-types' => 'Beschränkungstypen:',
	'throttleoverride-types-all' => 'Alle Typen',
	'throttleoverride-types-actcreate' => 'Kontenerstellung',
	'throttleoverride-types-edit' => 'Seitenbearbeitungen',
	'throttleoverride-types-move' => 'Seitenverschiebungen',
	'throttleoverride-types-mailpassword' => 'Passwortwiederherstellungs-Mails',
	'throttleoverride-types-emailuser' => 'Benutzer-Mails',
	'throttleoverride-list-throttletype' => 'Beschränkungstyp:',
	'throttleoverride-list-legend' => 'Ausnahmefilterung',
	'throttleoverride-list-rangestart' => 'Start des IP-Adressbereichs',
	'throttleoverride-list-rangeend' => 'Ende des IP-Adressbereichs',
	'throttleoverride-list-expiry' => 'Ablauf',
	'throttleoverride-list-type' => 'Erlaubte Aktionen',
	'throttleoverride-list-reason' => 'Grund',
	'throttleoverride-list-search' => 'Suchen',
	'throttleoverride-list-noresults' => 'Die Liste der Beschränkungsumgehungen ist leer.',
	'throttleoverride-validation-ipinvalid' => 'Ungültige IP-Adresse oder ungültiger IP-Adressenbereich.',
	'throttleoverride-validation-rangedisabled' => 'Die Möglichkeit zur Erstellung von Drosselungsausnahmen bei IP-Adressbereichen ist deaktiviert.',
	'throttleoverride-validation-rangetoolarge' => 'Drosselungsausnahmen bei IP-Adressbereichen größer als $1 sind nicht erlaubt.',
);

/** Spanish (español)
 * @author Ovruni
 */
$messages['es'] = array(
	'throttleoverride-list-search' => 'Buscar',
);

/** Persian (فارسی)
 * @author Armin1392
 * @author Reza1615
 */
$messages['fa'] = array(
	'throttleoverride-legend' => 'اطلاعات معافیت',
	'throttleoverride-ipaddress' => 'آدرس آی‌پی یا محدوده',
	'throttleoverride-success' => 'معافیت اجرا شد.',
	'throttleoverride-types-all' => 'همهٔ انواع',
	'throttleoverride-types-actcreate' => 'ایجاد حساب',
	'throttleoverride-types-edit' => 'ویرایش‌های صفحه',
	'throttleoverride-types-move' => 'حرکات صفحه',
	'throttleoverride-types-mailpassword' => 'رایانامه‌های بازیابی رمز عبور',
	'throttleoverride-types-emailuser' => 'رایانامه‌های کاربر',
	'throttleoverride-list-legend' => 'فیلتر معافیت',
	'throttleoverride-list-rangestart' => 'آغاز محدودهٔ آی‌پی',
	'throttleoverride-list-rangeend' => 'پایان محدودهٔ‌ آی‌پی',
	'throttleoverride-list-expiry' => 'انقضاء',
	'throttleoverride-list-type' => 'عملیات مجاز',
	'throttleoverride-list-reason' => 'دلیل',
	'throttleoverride-list-search' => 'جستجو',
);

/** French (français)
 * @author Gomoko
 * @author Louperivois
 */
$messages['fr'] = array(
	'overridethrottle' => 'Écraser la restriction de création de compte',
	'throttleoverridelist' => 'Liste des outrepassements de limites',
	'throttleoverride-desc' => 'Permet l’écrasement des restrictions d’adresses IP',
	'throttleoverride-legend' => 'Information sur l’exemption',
	'throttleoverride-text' => 'Entrez l’adresse IP ou la plage que vous voulez exempter de certaines restrictions, et la durée de vie de l’exemption.
Un motif facultatif peut être fourni pour les journaux.',
	'throttleoverride-ipaddress' => 'Adresse IP ou plage',
	'throttleoverride-success' => 'L’exemption a été appliquée.',
	'throttleoverride-types' => 'Types de restriction:',
	'throttleoverride-types-all' => 'Tous les types',
	'throttleoverride-types-actcreate' => 'Création de compte',
	'throttleoverride-types-edit' => 'Modifications de page',
	'throttleoverride-types-move' => 'Déplacements de page',
	'throttleoverride-types-mailpassword' => 'Courriels de récupération de mot de passe',
	'throttleoverride-types-emailuser' => 'Courriels utilisateur',
	'throttleoverride-list-throttletype' => 'Type de limite :',
	'throttleoverride-list-legend' => 'Filtrage des exemptions',
	'throttleoverride-list-rangestart' => "Début de la plage d'adresses IP",
	'throttleoverride-list-rangeend' => "Fin de la plage d'adresses IP",
	'throttleoverride-list-expiry' => 'Expiration',
	'throttleoverride-list-type' => 'Actions autorisées',
	'throttleoverride-list-reason' => 'Motif',
	'throttleoverride-list-search' => 'Rechercher',
	'throttleoverride-list-noresults' => 'La liste des outrepassements de limites est vide.',
	'throttleoverride-validation-ipinvalid' => 'Adresse IP non valide ou plage d’adresse IP.',
	'throttleoverride-validation-rangedisabled' => 'La possibilité de créer des exemptions d’accélérateur sur des plages d’adresses IP est désactivée.',
	'throttleoverride-validation-rangetoolarge' => 'Les exemptions d’accélérateur sur une plage d’adresses IP plus grande que $1 ne sont pas autorisées.',
);

/** Galician (galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'overridethrottle' => 'Ignorar a restrición de creación de contas',
	'throttleoverridelist' => 'Lista de restricións ignoradas',
	'throttleoverride-desc' => 'Permite ignorar as restricións dos enderezos IP',
	'throttleoverride-legend' => 'Información sobre a exención',
	'throttleoverride-text' => 'Insira o enderezo IP ou o rango que queira eximir de certas restricións, así como a duración da exención.
Pode especificar un motivo opcional para os rexistros.',
	'throttleoverride-ipaddress' => 'Enderezo IP ou rango',
	'throttleoverride-success' => 'Aplicouse a exención.',
	'throttleoverride-types' => 'Tipos de restrición:',
	'throttleoverride-types-all' => 'Todos os tipos',
	'throttleoverride-types-actcreate' => 'Creación de contas',
	'throttleoverride-types-edit' => 'Edicións da páxina',
	'throttleoverride-types-move' => 'Traslados da páxina',
	'throttleoverride-types-mailpassword' => 'Correos electrónicos de recuperación do contrasinal',
	'throttleoverride-types-emailuser' => 'Correos electrónicos do usuario',
	'throttleoverride-list-throttletype' => 'Tipo de restrición:',
	'throttleoverride-list-legend' => 'Filtro de exencións',
	'throttleoverride-list-rangestart' => 'Inicio do rango de enderezos IP',
	'throttleoverride-list-rangeend' => 'Fin do rango de enderezos IP',
	'throttleoverride-list-expiry' => 'Caducidade',
	'throttleoverride-list-type' => 'Accións permitidas',
	'throttleoverride-list-reason' => 'Motivo',
	'throttleoverride-list-search' => 'Procurar',
	'throttleoverride-list-noresults' => 'A lista de restricións ignoradas está baleira.',
	'throttleoverride-validation-ipinvalid' => 'O enderezo IP ou o rango do enderezo IP non é válido.',
	'throttleoverride-validation-rangedisabled' => 'Está desactivada a posibilidade de crear exencións de restricións nos rangos dos enderezos IP.',
	'throttleoverride-validation-rangetoolarge' => 'Non están permitidas as restricións nun rango de enderezos IP maior que $1.',
);

/** Italian (italiano)
 * @author Beta16
 */
$messages['it'] = array(
	'overridethrottle' => 'Ignora la limitazione nella creazione di utenze',
	'throttleoverride-desc' => 'Consente di ignorare le limitazioni per indirizzo IP',
	'throttleoverride-legend' => "Informazioni sull'esenzione",
	'throttleoverride-text' => "Inserisci l'indirizzo IP o l'intervallo che si vuole rendere esente da alcune limitazioni, e per quanto tempo l'esenzione deve durare.
Puoi indicare anche un motivo che sarà visibile nei registri.",
	'throttleoverride-ipaddress' => 'Indirizzo IP o intervallo',
	'throttleoverride-success' => "L'esenzione è stata applicata.",
	'throttleoverride-types' => 'Tipi di limitazioni:',
	'throttleoverride-types-actcreate' => 'Creazione di utenze',
	'throttleoverride-types-edit' => 'Modifiche alle pagine',
	'throttleoverride-types-move' => 'Spostamenti di pagine',
	'throttleoverride-types-mailpassword' => 'Email di recupero password',
	'throttleoverride-types-emailuser' => 'Email agli utenti',
);

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'overridethrottle' => 'アカウント作成の制限の回避',
	'throttleoverride-desc' => 'IP アドレスの制限を回避できるようにする',
	'throttleoverride-legend' => '免除の情報',
	'throttleoverride-text' => '制限を免除する IP アドレスまたは範囲、およびその制限の期間を入力してください。
記録用の理由は省略できます。',
	'throttleoverride-ipaddress' => 'IP アドレスまたは範囲',
	'throttleoverride-success' => '免除を適用しました。',
	'throttleoverride-types' => '制限の種類:',
	'throttleoverride-types-all' => 'すべての種類',
	'throttleoverride-types-actcreate' => 'アカウント作成',
	'throttleoverride-types-edit' => 'ページの編集',
	'throttleoverride-types-move' => 'ページの移動',
	'throttleoverride-types-mailpassword' => 'パスワード再発行メールの送信',
	'throttleoverride-types-emailuser' => '利用者へのメール送信',
	'throttleoverride-list-throttletype' => '制限の種類:',
	'throttleoverride-list-legend' => 'フィルタリングの免除',
	'throttleoverride-list-rangestart' => 'IP 範囲の始点',
	'throttleoverride-list-rangeend' => 'IP 範囲の終点',
	'throttleoverride-list-expiry' => '有効期限',
	'throttleoverride-list-type' => '許可された操作',
	'throttleoverride-list-reason' => '理由',
	'throttleoverride-list-search' => '検索',
	'throttleoverride-validation-ipinvalid' => 'IP アドレスまたは IP 範囲が無効です。',
);

/** Korean (한국어)
 * @author Priviet
 * @author 아라
 */
$messages['ko'] = array(
	'overridethrottle' => '계정 만들기 제한 회피',
	'throttleoverridelist' => '스로틀 무시 목록',
	'throttleoverride-desc' => 'IP 주소의 제한을 회피할 수 있습니다',
	'throttleoverride-legend' => '면제 정보',
	'throttleoverride-text' => '제한을 면재할 IP 주소나 범위와 제한할 기간을 입력하세요.
선택적인 이유는 기록에 제공할 수 있습니다.',
	'throttleoverride-ipaddress' => 'IP 주소나 범위',
	'throttleoverride-success' => '면제를 적용했습니다.',
	'throttleoverride-types' => '제한 유형:',
	'throttleoverride-types-all' => '모든 종류',
	'throttleoverride-types-actcreate' => '계정 만들기',
	'throttleoverride-types-edit' => '문서 편집',
	'throttleoverride-types-move' => '문서 옮기기',
	'throttleoverride-types-mailpassword' => '비밀번호 복구 이메일',
	'throttleoverride-types-emailuser' => '사용자에게 이메일 보내기',
	'throttleoverride-list-throttletype' => '스로틀 종류:',
	'throttleoverride-list-legend' => '예외 필터링',
	'throttleoverride-list-rangestart' => 'IP 범위의 시작',
	'throttleoverride-list-rangeend' => 'IP 범위의 끝',
	'throttleoverride-list-expiry' => '만료',
	'throttleoverride-list-type' => '허용된 명령',
	'throttleoverride-list-reason' => '이유',
	'throttleoverride-list-search' => '검색',
	'throttleoverride-list-noresults' => '스로틀 무시 목록이 비었습니다.',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 * @author Soued031
 */
$messages['lb'] = array(
	'throttleoverride-legend' => "Informatioun iwwer d'Ausnam",
	'throttleoverride-ipaddress' => 'IP-Adress oder Range',
	'throttleoverride-success' => "D'Ausnahm gouf applizéiert.",
	'throttleoverride-types-actcreate' => 'Benotzerkont opmaachen',
	'throttleoverride-types-edit' => 'Säitenännerungen',
	'throttleoverride-types-move' => 'Geréckelt Säiten',
	'throttleoverride-types-emailuser' => 'E-Mailen u Benotzer',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'overridethrottle' => 'Избегни го ограничувањето за создавање сметки',
	'throttleoverridelist' => 'Список на наметнати презапишувања',
	'throttleoverride-desc' => 'Овозможува избегнување на ограничувања на IP-адреси',
	'throttleoverride-legend' => 'Информации за изземањето',
	'throttleoverride-text' => 'Внесете IP-адреса или опсег од адреси што сакате да ги изземете од ограничувања и колку долго да трае изземањето.
За евиденција во дневниците, можете да дадете и образложение.',
	'throttleoverride-ipaddress' => 'IP-адреса или опсег',
	'throttleoverride-success' => 'Изземањето е применето.',
	'throttleoverride-types' => 'Типови на ограничувања:',
	'throttleoverride-types-all' => 'Сите видови',
	'throttleoverride-types-actcreate' => 'Создавање на сметка',
	'throttleoverride-types-edit' => 'Уредување на страници',
	'throttleoverride-types-move' => 'Преместување на страници',
	'throttleoverride-types-mailpassword' => 'Повраќање на лозинка по е-пошта',
	'throttleoverride-types-emailuser' => 'Е-пошта од корисникот',
	'throttleoverride-list-throttletype' => 'Вид на наметнување:',
	'throttleoverride-list-legend' => 'Филтрирање по изземања',
	'throttleoverride-list-rangestart' => 'Почеток на IP-опсегот',
	'throttleoverride-list-rangeend' => 'Крај на IP-опсегот',
	'throttleoverride-list-expiry' => 'Истек',
	'throttleoverride-list-type' => 'Дозволени дејства:',
	'throttleoverride-list-reason' => 'Причина',
	'throttleoverride-list-search' => 'Пребарај',
	'throttleoverride-list-noresults' => 'Списокот за наметнати презапишувања е празен.',
	'throttleoverride-validation-ipinvalid' => 'Неважечка IP-адреса или IP-опсег.',
	'throttleoverride-validation-rangedisabled' => 'Можноста за создавање на исклучоци од ограничувањето на IP-опсези е исклучена.',
	'throttleoverride-validation-rangetoolarge' => 'Не се одзволени исклучоци од ограничувањето по IP-опсег поголем од $1.',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'overridethrottle' => 'Beperkingen voor aanmaken gebruikers negeren',
	'throttleoverridelist' => 'Lijst met drempelwaardebeperkingen',
	'throttleoverride-desc' => 'Maakt het mogelijk de beperkingen voor IP-adressen te negeren',
	'throttleoverride-legend' => 'Gegevens over uitzonderingen',
	'throttleoverride-text' => 'Voer een IP-adres of een IP-reeks in waar u uitzonderingen voor wilt instellen, en hoe lang de uitzondering moet duren.
U kunt optioneel een reden opgeven voor in het logboek.',
	'throttleoverride-ipaddress' => 'IP-adres of -reeks',
	'throttleoverride-success' => 'De uitzondering is toegepast.',
	'throttleoverride-types' => 'Beperkingstypen:',
	'throttleoverride-types-all' => 'Alle typen',
	'throttleoverride-types-actcreate' => 'Gebruikers aanmaken',
	'throttleoverride-types-edit' => "Pagina's bewerken",
	'throttleoverride-types-move' => "Pagina's hernoemen",
	'throttleoverride-types-mailpassword' => 'Wachtwoord per e-mail verzenden',
	'throttleoverride-types-emailuser' => 'E-mails naar gebruikers',
	'throttleoverride-list-throttletype' => 'Beperkingstype:',
	'throttleoverride-list-legend' => 'Vrijstellingsfilters',
	'throttleoverride-list-rangestart' => 'Begin van IP-reeks',
	'throttleoverride-list-rangeend' => 'Einde van IP-reeks',
	'throttleoverride-list-expiry' => 'Vervalt',
	'throttleoverride-list-type' => 'Toegestane handelingen',
	'throttleoverride-list-reason' => 'Reden',
	'throttleoverride-list-search' => 'Zoeken',
	'throttleoverride-list-noresults' => 'De lijst met drempelwaardebeperkingen is leeg.',
);

/** Occitan (occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'overridethrottle' => 'Espotir la restriccion de creacion de compte',
	'throttleoverridelist' => 'Lista dels otrapassaments de limits',
	'throttleoverride-desc' => 'Permet l’espotiment de las restriccions d’adreças IP',
	'throttleoverride-legend' => 'Informacion sus l’exempcion',
	'throttleoverride-ipaddress' => 'Adreça IP o plaja',
	'throttleoverride-success' => 'L’exempcion es estada aplicada.',
	'throttleoverride-types' => 'Tipes de restriccion :',
	'throttleoverride-types-all' => 'Totes los tipes',
	'throttleoverride-types-actcreate' => 'Creacion de compte',
	'throttleoverride-types-edit' => 'Modificacions de pagina',
	'throttleoverride-types-move' => 'Desplaçaments de pagina',
	'throttleoverride-types-mailpassword' => 'Corrièrs electronics de recuperacion de senhal',
	'throttleoverride-types-emailuser' => 'Corrièrs electronics utilizaire',
	'throttleoverride-list-throttletype' => 'Tipe de limit :',
	'throttleoverride-list-legend' => 'Filtratge de las exempcions',
	'throttleoverride-list-rangestart' => "Començament de la plaja d'adreças IP",
	'throttleoverride-list-rangeend' => "Fin de la plaja d'adreças IP",
	'throttleoverride-list-expiry' => 'Expiracion',
	'throttleoverride-list-type' => 'Accions autorizadas',
	'throttleoverride-list-reason' => 'Motiu',
	'throttleoverride-list-search' => 'Recercar',
	'throttleoverride-list-noresults' => 'La lista dels otrapassaments de limits es voida.',
);

/** Polish (polski)
 * @author Chrumps
 */
$messages['pl'] = array(
	'throttleoverride-validation-ipinvalid' => 'Nieprawidłowy adres IP lub zakres adresów IP.',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Luckas
 */
$messages['pt-br'] = array(
	'throttleoverride-types-all' => 'Todos os tipos',
	'throttleoverride-list-type' => 'Ações permitidas',
	'throttleoverride-list-reason' => 'Motivo',
	'throttleoverride-list-search' => 'Pesquisar',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'throttleoverride-ipaddress' => 'Indirizze IP o indervalle',
	'throttleoverride-success' => "L'eccezzione ha state applicate.",
	'throttleoverride-types-edit' => "Cangiaminde d'a pàgene",
	'throttleoverride-types-move' => "Spustaminde d'a pàgene",
	'throttleoverride-types-mailpassword' => "Recupere d'a password cu l'email",
	'throttleoverride-types-emailuser' => "Email de l'utende",
);

/** Russian (русский)
 * @author Okras
 */
$messages['ru'] = array(
	'overridethrottle' => 'Переопределить ограничения на создание учётных записей',
	'throttleoverridelist' => 'Список переопределений ограничений',
	'throttleoverride-desc' => 'Позволяет переопределение ограничения для IP-адреса',
	'throttleoverride-legend' => 'Информация об исключениях',
	'throttleoverride-text' => 'Введите IP-адрес или диапазон адресов, для которых вы хотите сделать исключение по некоторым ограничениям, и как долго должен длиться это исключение.
Можно указать дополнительные причины для журналов.',
	'throttleoverride-ipaddress' => 'IP-адрес или диапазон',
	'throttleoverride-success' => 'Исключение было применено.',
	'throttleoverride-types' => 'Типы ограничений:',
	'throttleoverride-types-all' => 'Все типы',
	'throttleoverride-types-actcreate' => 'Создание учётной записи',
	'throttleoverride-types-edit' => 'Правки страницы',
	'throttleoverride-types-move' => 'Переименования страницы',
	'throttleoverride-types-mailpassword' => 'Адреса восстановления пароля',
	'throttleoverride-types-emailuser' => 'Пользовательские адреса электронной почты',
	'throttleoverride-list-throttletype' => 'Типы ограничений:',
	'throttleoverride-list-legend' => 'Фильтрация исключений',
	'throttleoverride-list-rangestart' => 'Начало диапазона IP-адресов',
	'throttleoverride-list-rangeend' => 'Конец диапазона IP-адресов',
	'throttleoverride-list-expiry' => 'Срок действия',
	'throttleoverride-list-type' => 'Допустимые действия',
	'throttleoverride-list-reason' => 'Причина',
	'throttleoverride-list-search' => 'Найти',
	'throttleoverride-list-noresults' => 'Список переопределений ограничений пуст.',
	'throttleoverride-validation-ipinvalid' => 'Недопустимый IP-адрес или диапазон IP.',
	'throttleoverride-validation-rangedisabled' => 'Возможность создания исключений с ограничением по IP-диапазону отключена.',
	'throttleoverride-validation-rangetoolarge' => 'Исключения с ограничением в IP-диапазонах больше, чем $1, не разрешены.',
);

/** Swedish (svenska)
 * @author Jopparn
 */
$messages['sv'] = array(
	'throttleoverride-types-all' => 'Alla typer',
	'throttleoverride-types-actcreate' => 'Kontoskapning',
	'throttleoverride-types-edit' => 'Redigeringar på sidan',
	'throttleoverride-types-move' => 'Flyttningar av sidan',
	'throttleoverride-types-mailpassword' => 'E-post för återhämtning av e-post',
	'throttleoverride-types-emailuser' => 'Användarens e-post',
	'throttleoverride-list-type' => 'Tillåtna åtgärder',
	'throttleoverride-list-reason' => 'Orsak',
	'throttleoverride-list-search' => 'Sök',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'throttleoverride-types-actcreate' => 'ఖాతా సృష్టింపు',
	'throttleoverride-types-edit' => 'పేజీ మార్పులు',
	'throttleoverride-types-move' => 'పేజీల తరలింపులు',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'overridethrottle' => 'Pangibabawan ang pagpigil sa paglikha ng akawnt',
	'throttleoverride-desc' => 'Nagpapahintulot sa pangingibabaw sa mga pampigil ng tirahang IP',
	'throttleoverride-legend' => 'Kabatiran sa hindi pagsasali',
	'throttleoverride-text' => 'Ipasok ang tirahang IP o saklaw na nais mong hindi isali mula sa partikular na mga pagpipigil, at kung hanggang kailan dapat magtagal ang hindi pagsasali.
Maaaring magbigay o hindi magbigay ng dahilan para sa mga talaan.',
	'throttleoverride-ipaddress' => 'Tirahang IP o saklaw',
	'throttleoverride-success' => 'Nailapat na ang hindi pagsasali.',
	'throttleoverride-types' => 'Mga uri ng pagpigil:',
	'throttleoverride-types-actcreate' => 'Paglikha ng akawnt',
	'throttleoverride-types-edit' => 'Mga pagbago sa pahina',
	'throttleoverride-types-move' => 'Mga paglipat ng pahina',
	'throttleoverride-types-mailpassword' => 'Mga elektronikong liham sa muling pagpapanumbalik ng hudyat',
	'throttleoverride-types-emailuser' => 'Mga elektronikong liham ng tagagamit',
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Base
 * @author Ата
 */
$messages['uk'] = array(
	'overridethrottle' => 'Обхід обмеження частоти створення облікових записів',
	'throttleoverridelist' => 'Список обходів обмеження частоти',
	'throttleoverride-desc' => 'Дозволити обходити обмеження частоти створення облікових записів',
	'throttleoverride-legend' => 'Інформація про звільнення',
	'throttleoverride-text' => "Введіть IP-адресу або діапазон, який Ви хочете звільнити від обмеження частоти, і як довго звільнення повинне тривати.
Не обов'язково можна вказати причину для вказання у журналах.",
	'throttleoverride-ipaddress' => 'IP-адреса або діапазон',
	'throttleoverride-success' => 'Звільнення було застосовано.',
	'throttleoverride-types' => 'Типи обмеження частоти:',
	'throttleoverride-types-all' => 'Всі типи',
	'throttleoverride-types-actcreate' => 'Створення облікових записів',
	'throttleoverride-types-edit' => 'Редагування сторінок',
	'throttleoverride-types-move' => 'Перейменування сторінок',
	'throttleoverride-types-mailpassword' => 'Відновлення паролю електронною поштою',
	'throttleoverride-types-emailuser' => 'Листи користувачам електронною поштою',
	'throttleoverride-list-throttletype' => 'Тип обмеження частоти:',
	'throttleoverride-list-legend' => 'Звільнення фільтрації',
	'throttleoverride-list-rangestart' => 'Початок діапазону IP',
	'throttleoverride-list-rangeend' => 'Кінець діапазону IP',
	'throttleoverride-list-expiry' => 'Закінчення',
	'throttleoverride-list-type' => 'Дозволені дії',
	'throttleoverride-list-reason' => 'Причина',
	'throttleoverride-list-search' => 'Пошук',
	'throttleoverride-list-noresults' => 'Список обходів обмеження частоти - порожній.',
	'throttleoverride-validation-ipinvalid' => 'Неприпустима IP-адреса або діапазон IP.',
	'throttleoverride-validation-rangedisabled' => 'Можливість створювати винятки з діапазонів IP-адрес відключена.',
	'throttleoverride-validation-rangetoolarge' => 'Винятки з обмеженнями у діапазонах IP-адрес більших, ніж $1, не дозволяються.',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Shirayuki
 * @author Xiaomingyan
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'overridethrottle' => '覆盖帐户创建节流阀',
	'throttleoverridelist' => '节流阀覆盖列表',
	'throttleoverride-desc' => '允许IP地址覆盖节流阀',
	'throttleoverride-legend' => '豁免信息',
	'throttleoverride-text' => '输入您想从某些节流阀中排除的IP地址或范围，以及豁免要持续多久。
可选为日志提供一个理由。',
	'throttleoverride-ipaddress' => 'IP地址或范围',
	'throttleoverride-success' => '此豁免已应用。',
	'throttleoverride-types' => '节流阀类型：',
	'throttleoverride-types-all' => '所有类型',
	'throttleoverride-types-actcreate' => '账户创建',
	'throttleoverride-types-edit' => '编辑页面',
	'throttleoverride-types-move' => '移动页面',
	'throttleoverride-types-mailpassword' => '密码恢复邮件',
	'throttleoverride-types-emailuser' => '用户电子邮箱',
	'throttleoverride-list-throttletype' => '节流阀类型：',
	'throttleoverride-list-legend' => '豁免筛选',
	'throttleoverride-list-rangestart' => 'IP范围的开始',
	'throttleoverride-list-rangeend' => 'IP范围的结束',
	'throttleoverride-list-expiry' => '截止日期',
	'throttleoverride-list-type' => '允许的动作',
	'throttleoverride-list-reason' => '原因',
	'throttleoverride-list-search' => '搜索',
	'throttleoverride-list-noresults' => '节流阀覆盖列表是空的。',
);

/** Traditional Chinese (中文（繁體）‎)
 * @author Simon Shek
 */
$messages['zh-hant'] = array(
	'throttleoverride-types-mailpassword' => '密碼恢復郵件',
);
