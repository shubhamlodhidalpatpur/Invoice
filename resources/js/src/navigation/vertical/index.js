/*

Array of object

Top level object can be:
1. Header
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- Header ---/

header

/--- nav Grp ---/

title
icon (if it's on top level)
tag
tagVariant
children

/--- nav Item ---/

icon (if it's on top level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)
tag
tagVariant

*/
import dashboard from './dashboard'
import appsAndPages from './apps-and-pages'
import others from './others'
import chartsAndMaps from './charts-and-maps'
import uiElements from './ui-elements'
import formAndTable from './forms-and-table'
import custom from './custom'
import sms from './sms'
import axios from "@axios";
// Array of sections
export default [
    {
        title: 'Template',
        icon: 'HomeIcon',
        children: [
            ...dashboard, ...appsAndPages, ...uiElements, ...formAndTable, ...chartsAndMaps, ...others, ...custom
        ]
    },
    {
        title: 'Invoice',
        icon: 'HomeIcon',
        children: [
            ...sms
        ]
    }
]
// export default [...custom]