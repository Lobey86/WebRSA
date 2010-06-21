#!/bin/bash
# http://svnbook.red-bean.com/en/1.4/svn.advanced.props.special.keywords.html
# svn checkout svn+ssh://cbuffin@svn.adullact.net/svnroot/webrsa/trunk/app .

f="`dirname "$0"`"
cd "$f"

# FIXME: ne fonctionne pas quand il y a plusieurs lignes !?!

# TODO: nettoyer /home/cbuffin/www/webrsa/app/tmp/cache (sauf les empty)
rm tmp/logs/debug.log.*
rm tmp/logs/error.log.*
rm tmp/logs/debug.log
rm tmp/logs/error.log

# http://snipt.net/nick/svn-delete-all-files-marked-for-deletion/
svn status . |grep '^!' |sed 's/^!      /svn delete "/g' | sed 's/$/"/g' | sh

# http://snipt.net/nick/svn-add-all-files-marked-for-add/
svn status . |grep '^?' |sed 's/^?      /svn add "/g' | sed 's/$/"/g' | sh

# svn status | grep "^\?" | sed -e 's/? *//' | sed -e 's/ /\\ /g' | xargs svn add
# svn status | grep "^\!" | sed -e 's/! *//' | sed -e 's/ /\\ /g' | xargs svn del

# http://svnbook.red-bean.com/en/1.1/ch07s02.html
NOW=`date +"%Y-%m-%d %H:%M:%S %z (%a, %d %b %Y)"`
sed -i "s/\\\$LastChangedDate[^\\\$]*\\\$/\$LastChangedDate: $NOW\$/g" views/elements/footer.ctp
svn propset svn:keywords "Date Author Revision" views/elements/footer.ctp

# svn copy \
# svn+ssh://cbuffin@svn.adullact.net/svnroot/webrsa/trunk/ \
# svn+ssh://cbuffin@svn.adullact.net/svnroot/webrsa/tags/1.0.3/ \
# -m "Version 1.0.3 (installation CG93 13/07/09 12h30)"

# svn export svn+ssh://cbuffin@svn.adullact.net/svnroot/webrsa/tags/1.0.2.1/


######## Ajout de la branche 1.0.7 pour les patchs #####################

# svn copy \
# svn+ssh://arnauz@svn.adullact.net/svnroot/webrsa/tags/1.0.7/ \
# svn+ssh://arnauz@svn.adullact.net/svnroot/webrsa/branches/1.0.7/ \
# -m "Création de la branche 1.0.7"
#
# svn checkout svn+ssh://arnauz@svn.adullact.net/svnroot/webrsa/branches/1.0.7/