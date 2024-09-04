# Import-Csv 'C:\adduser.csv' | ForEach-Object {New-ADUser -SamAccountName $_.SamAccountName -GivenName $_.GivenName -Surname $_.sn -Name $_.Name -UserPrincipalName $_.UserPrincipalName -DisplayName $_.DisplayName -company $_.company -department $_.department -EmailAddress $_.mail -Path $_.Path -AccountPassword(ConvertTo-SecureString -AsPlainText $_.AccountPassword -Force) -Enabled 1}
Import-Csv 'C:\test.csv' | ForEach-Object {
    $SamAccountName = $_.SamAccountName
    Get-ADUser -Filter {SamAccountName -eq $SamAccountName} | Set-ADUser -add @{UserPrincipalName = $_.UserPrincipalName;"DisplayName" = $_."DisplayName"; `
    mail = $_.mail; extensionAttribute6 = $_.extensionAttribute6;extensionAttribute7 = $_.extensionAttribute7;extensionAttribute8 = $_.extensionAttribute8; `
    extensionAttribute9 = $_.extensionAttribute9}

} 
