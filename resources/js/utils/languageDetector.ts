// Умная система определения языка программирования
import type { ProgrammingLanguage } from '@/types';

// Паттерны для определения языков
const LANGUAGE_PATTERNS: Record<ProgrammingLanguage, RegExp[]> = {
    // Основные языки
    'php': [
        /<\?php/,
        /<\?=/,
        /function\s+\w+\s*\(/,
        /\$[a-zA-Z_]\w*/,
        /echo\s+/,
        /return\s+/,
        /class\s+\w+/,
        /namespace\s+/,
        /use\s+/,
        /public\s+function/,
        /private\s+function/,
        /protected\s+function/
    ],
    'javascript': [
        /function\s+\w+\s*\(/,
        /const\s+\w+/,
        /let\s+\w+/,
        /var\s+\w+/,
        /console\.log/,
        /=>\s*{/,
        /import\s+/,
        /export\s+/,
        /\.forEach\(/,
        /\.map\(/,
        /\.filter\(/,
        /new\s+Date\(/,
        /JSON\./,
        /Promise\./
    ],
    'typescript': [
        /interface\s+\w+/,
        /type\s+\w+/,
        /:\s*\w+(\[\])?/,
        /as\s+\w+/,
        /enum\s+\w+/,
        /implements\s+/,
        /extends\s+/,
        /<[A-Z][a-zA-Z]*>/,
        /readonly\s+/,
        /private\s+\w+/,
        /public\s+\w+/,
        /protected\s+\w+/,
        /import\s+type/,
        /export\s+type/,
        /export\s+interface/,
        /export\s+enum/,
        /export\s+const/,
        /export\s+function/,
        /export\s+class/,
        /export\s+default/,
        /export\s*{/,
        /export\s*\*/,
        /import\s*{/,
        /import\s*\*/,
        /import\s+['"][^'"]*['"]/,
        /from\s+['"][^'"]*['"]/,
        /declare\s+module/,
        /declare\s+namespace/,
        /declare\s+function/,
        /declare\s+const/,
        /declare\s+class/,
        /declare\s+interface/,
        /declare\s+type/,
        /declare\s+enum/,
        /declare\s+var/,
        /declare\s+let/,
        /declare\s+const/,
        /declare\s+global/,
        /declare\s+export/,
        /declare\s+import/,
        /declare\s+require/,
        /declare\s+module\.exports/,
        /declare\s+exports/,
        /declare\s+__dirname/,
        /declare\s+__filename/,
        /declare\s+process/,
        /declare\s+Buffer/,
        /declare\s+console/,
        /declare\s+setTimeout/,
        /declare\s+setInterval/,
        /declare\s+clearTimeout/,
        /declare\s+clearInterval/,
        /declare\s+setImmediate/,
        /declare\s+clearImmediate/,
        /declare\s+require/,
        /declare\s+module/,
        /declare\s+exports/,
        /declare\s+__dirname/,
        /declare\s+__filename/,
        /declare\s+process/,
        /declare\s+Buffer/,
        /declare\s+console/,
        /declare\s+setTimeout/,
        /declare\s+setInterval/,
        /declare\s+clearTimeout/,
        /declare\s+clearInterval/,
        /declare\s+setImmediate/,
        /declare\s+clearImmediate/,
        /:\s*RouteRecordRaw/,
        /:\s*RouteRecordRaw\[\]/,
        /:\s*createApp/,
        /:\s*createPinia/,
        /:\s*createRouter/,
        /:\s*createWebHistory/,
        /:\s*App/,
        /:\s*Vue/,
        /:\s*Pinia/,
        /:\s*Router/,
        /:\s*History/,
        /:\s*Component/,
        /:\s*Ref/,
        /:\s*Reactive/,
        /:\s*Computed/,
        /:\s*Watch/,
        /:\s*OnMounted/,
        /:\s*OnUnmounted/,
        /:\s*OnUpdated/,
        /:\s*OnBeforeMount/,
        /:\s*OnBeforeUnmount/,
        /:\s*OnBeforeUpdate/,
        /:\s*OnErrorCaptured/,
        /:\s*OnRenderTracked/,
        /:\s*OnRenderTriggered/,
        /:\s*OnActivated/,
        /:\s*OnDeactivated/,
        /:\s*OnServerPrefetch/,
        /:\s*DefineComponent/,
        /:\s*ComponentPublicInstance/,
        /:\s*ComponentCustomProperties/,
        /:\s*ComponentCustomProps/,
        /:\s*VNode/,
        /:\s*VNodeChild/,
        /:\s*VNodeArrayChildren/,
        /:\s*VNodeNormalizedChildren/,
        /:\s*VNodeRef/,
        /:\s*VNodeProps/,
        /:\s*VNodeData/,
        /:\s*VNodeDirective/,
        /:\s*VNodeHook/,
        /:\s*VNodeTransition/,
        /:\s*VNodeTransitionGroup/,
        /:\s*VNodeKeepAlive/,
        /:\s*VNodeSuspense/,
        /:\s*VNodeTeleport/,
        /:\s*VNodeFragment/,
        /:\s*VNodeComment/,
        /:\s*VNodeText/,
        /:\s*VNodeStatic/,
        /:\s*VNodeClone/,
        /:\s*VNodeSlot/,
        /:\s*VNodeSlotOutlet/,
        /:\s*VNodeSlotFragment/,
        /:\s*VNodeSlotText/,
        /:\s*VNodeSlotComment/,
        /:\s*VNodeSlotStatic/,
        /:\s*VNodeSlotClone/,
        /:\s*VNodeSlotTeleport/,
        /:\s*VNodeSlotSuspense/,
        /:\s*VNodeSlotKeepAlive/,
        /:\s*VNodeSlotTransition/,
        /:\s*VNodeSlotTransitionGroup/,
        /:\s*VNodeSlotFragment/,
        /:\s*VNodeSlotText/,
        /:\s*VNodeSlotComment/,
        /:\s*VNodeSlotStatic/,
        /:\s*VNodeSlotClone/,
        /:\s*VNodeSlotTeleport/,
        /:\s*VNodeSlotSuspense/,
        /:\s*VNodeSlotKeepAlive/,
        /:\s*VNodeSlotTransition/,
        /:\s*VNodeSlotTransitionGroup/
    ],
    'python': [
        /def\s+\w+\s*\(/,
        /import\s+/,
        /from\s+\w+\s+import/,
        /if\s+__name__\s*==\s*['"]__main__['"]/,
        /print\s*\(/,
        /\.append\(/,
        /\.extend\(/,
        /list\(/,
        /dict\(/,
        /set\(/,
        /lambda\s+/,
        /with\s+open\(/,
        /try:\s*$/,
        /except\s+/
    ],
    'java': [
        /public\s+class\s+\w+/,
        /private\s+\w+/,
        /public\s+static\s+void\s+main/,
        /System\.out\.println/,
        /import\s+java\./,
        /package\s+/,
        /extends\s+/,
        /implements\s+/,
        /new\s+\w+\(/,
        /ArrayList<|HashMap<|LinkedList</,
        /@Override/,
        /@Deprecated/,
        /throws\s+/
    ],
    'csharp': [
        /using\s+System/,
        /namespace\s+\w+/,
        /public\s+class\s+\w+/,
        /private\s+\w+/,
        /Console\.WriteLine/,
        /var\s+\w+/,
        /List<\w+>/,
        /Dictionary<\w+,\w+>/,
        /async\s+Task/,
        /await\s+/,
        /[a-zA-Z_]\w*\.Add\(/,
        /[a-zA-Z_]\w*\.Remove\(/,
        /[a-zA-Z_]\w*\.Contains\(/
    ],
    'cpp': [
        /#include\s+<[^>]+>/,
        /using\s+namespace\s+std/,
        /std::/,
        /cout\s*<<\s*/,
        /cin\s*>>\s*/,
        /vector<\w+>/,
        /map<\w+,\w+>/,
        /template\s*</,
        /class\s+\w+\s*{/,
        /public:/,
        /private:/,
        /protected:/,
        /virtual\s+/
    ],
    'go': [
        /package\s+main/,
        /import\s+\(/,
        /func\s+main\(\)/,
        /fmt\.Println/,
        /fmt\.Printf/,
        /var\s+\w+/,
        /:=/,
        /make\(/,
        /append\(/,
        /range\s+/,
        /go\s+func/,
        /chan\s+\w+/,
        /interface\s*{/
    ],
    'rust': [
        /fn\s+\w+\s*\(/,
        /let\s+mut\s+\w+/,
        /let\s+\w+/,
        /println!/,
        /println!\(/,
        /Vec::new\(/,
        /HashMap::new\(/,
        /match\s+\w+\s*{/,
        /Some\(/,
        /None/,
        /Result<|Option</,
        /impl\s+\w+/,
        /trait\s+\w+/,
        /struct\s+\w+/
    ],
    'html': [
        /<!DOCTYPE\s+html>/,
        /<html[^>]*>/,
        /<head[^>]*>/,
        /<body[^>]*>/,
        /<div[^>]*>/,
        /<span[^>]*>/,
        /<p[^>]*>/,
        /<h[1-6][^>]*>/,
        /<a\s+href/,
        /<img[^>]*>/,
        /<form[^>]*>/,
        /<input[^>]*>/,
        /<button[^>]*>/,
        /<meta[^>]*>/,
        /<title[^>]*>/,
        /<link[^>]*>/,
        /<script[^>]*>/,
        /<style[^>]*>/,
        /<nav[^>]*>/,
        /<header[^>]*>/,
        /<footer[^>]*>/,
        /<main[^>]*>/,
        /<section[^>]*>/,
        /<article[^>]*>/,
        /<aside[^>]*>/,
        /<ul[^>]*>/,
        /<ol[^>]*>/,
        /<li[^>]*>/,
        /<table[^>]*>/,
        /<tr[^>]*>/,
        /<td[^>]*>/,
        /<th[^>]*>/,
        /<thead[^>]*>/,
        /<tbody[^>]*>/,
        /<tfoot[^>]*>/
    ],
    'css': [
        /{[^}]*}/,
        /:\s*[^;]+;/,
        /@media\s+/,
        /@keyframes\s+/,
        /@import\s+/,
        /background:/,
        /color:/,
        /font-size:/,
        /margin:/,
        /padding:/,
        /border:/,
        /display:\s*(block|inline|flex|grid)/,
        /position:\s*(relative|absolute|fixed|sticky)/
    ],
    'sql': [
        /SELECT\s+.+FROM/,
        /INSERT\s+INTO/,
        /UPDATE\s+\w+\s+SET/,
        /DELETE\s+FROM/,
        /CREATE\s+TABLE/,
        /ALTER\s+TABLE/,
        /DROP\s+TABLE/,
        /WHERE\s+/,
        /ORDER\s+BY/,
        /GROUP\s+BY/,
        /JOIN\s+/,
        /INNER\s+JOIN/,
        /LEFT\s+JOIN/,
        /RIGHT\s+JOIN/
    ],
    // Смешанные типы
    'php-html': [
        /<\?php.*?>/,
        /<\?=.*?>/,
        /<div[^>]*>.*?<\?php/,
        /<\?php.*?<div/,
        /echo\s+['"][^'"]*<[^>]+>[^'"]*['"]/,
        /<[^>]+>\s*<\?php/,
        /<\?php\s+[^?]*\?>\s*<[^>]+>/
    ],
    'vue': [
        /<template>/,
        /<script\s+setup/,
        /<script\s+lang="ts"/,
        /defineProps/,
        /defineEmits/,
        /ref\(/,
        /reactive\(/,
        /computed\(/,
        /watch\(/,
        /onMounted\(/,
        /v-if=/,
        /v-for=/,
        /v-model=/,
        /@click=/,
        /@input=/
    ],
    'blade': [
        /@if\s*\(/,
        /@foreach\s*\(/,
        /@for\s*\(/,
        /@while\s*\(/,
        /@switch\s*\(/,
        /@case\s+/,
        /@default/,
        /@break/,
        /@continue/,
        /@include\s*\(/,
        /@extends\s*\(/,
        /@section\s*\(/,
        /@yield\s*\(/,
        /{{.*?}}/,
        /{!!.*?!!}/,
        /@vite\(/,
        /@csrf/,
        /@method/,
        /@error/,
        /@auth/,
        /@guest/,
        /@config\(/,
        /@app\(/,
        /@env\(/,
        /@lang\(/,
        /@choice\(/,
        /@can\(/,
        /@cannot\(/,
        /@hasSection\(/,
        /@hasAny\(/,
        /@unless\(/,
        /@empty\(/,
        /@endempty/,
        /@isset\(/,
        /@endisset/,
        /@unset\(/,
        /@endunset/,
        /@production/,
        /@endproduction/,
        /@env\(/,
        /@endenv/,
        /@push\(/,
        /@endpush/,
        /@stack\(/,
        /@prepend\(/,
        /@endprepend/,
        /@inject\(/,
        /@component\(/,
        /@endcomponent/,
        /@slot\(/,
        /@endslot/,
        /@props\(/,
        /@once/,
        /@endonce/,
        /@verbatim/,
        /@endverbatim/
    ],
    'jsx': [
        /import\s+React/,
        /from\s+['"]react['"]/,
        /function\s+\w+\s*\([^)]*\)\s*{/,
        /const\s+\w+\s*=\s*\([^)]*\)\s*=>/,
        /return\s*\(/,
        /<[A-Z][a-zA-Z]*[^>]*>/,
        /className=/,
        /onClick=/,
        /onChange=/,
        /useState\(/,
        /useEffect\(/,
        /useContext\(/,
        /useRef\(/
    ],
    'tsx': [
        /import\s+React/,
        /from\s+['"]react['"]/,
        /interface\s+\w+\s*{/,
        /type\s+\w+\s*=/,
        /:\s*React\.FC/,
        /:\s*JSX\.Element/,
        /useState<[^>]+>/,
        /useEffect<[^>]+>/,
        /useContext<[^>]+>/,
        /useRef<[^>]+>/,
        /<[A-Z][a-zA-Z]*[^>]*>/,
        /className=/,
        /onClick=/,
        /onChange=/
    ],
    'html-css': [
        /<style[^>]*>/,
        /<link[^>]*rel="stylesheet"/,
        /<div[^>]*class=/,
        /<span[^>]*class=/,
        /<p[^>]*class=/,
        /{[^}]*}/,
        /:\s*[^;]+;/,
        /@media\s+/,
        /@keyframes\s+/,
        /background:/,
        /color:/,
        /font-size:/,
        /margin:/,
        /padding:/
    ],
    'html-js': [
        /<script[^>]*>/,
        /<script\s+src=/,
        /onclick=/,
        /onload=/,
        /onchange=/,
        /oninput=/,
        /addEventListener\(/,
        /document\./,
        /window\./,
        /console\./,
        /function\s+\w+\s*\(/,
        /const\s+\w+/,
        /let\s+\w+/,
        /var\s+\w+/
    ],
    'php-blade': [
        /@if\s*\(/,
        /@foreach\s*\(/,
        /@for\s*\(/,
        /@while\s*\(/,
        /@switch\s*\(/,
        /@case\s+/,
        /@default/,
        /@break/,
        /@continue/,
        /@include\s*\(/,
        /@extends\s*\(/,
        /@section\s*\(/,
        /@yield\s*\(/,
        /{{.*?}}/,
        /{!!.*?!!}/,
        /@vite\(/,
        /@csrf/,
        /@method/,
        /@error/,
        /@auth/,
        /@guest/,
        /<\?php/,
        /<\?=/,
        /function\s+\w+\s*\(/,
        /echo\s+/,
        /return\s+/
    ],
    // Дополнительные языки
    'ruby': [
        /def\s+\w+/,
        /class\s+\w+/,
        /module\s+\w+/,
        /puts\s+/,
        /print\s+/,
        /require\s+['"]/,
        /gem\s+['"]/,
        /attr_accessor/,
        /attr_reader/,
        /attr_writer/,
        /do\s*\|/,
        /end\s*$/,
        /if\s+\w+\s*$/,
        /unless\s+\w+\s*$/
    ],
    'swift': [
        /import\s+\w+/,
        /func\s+\w+\s*\(/,
        /class\s+\w+/,
        /struct\s+\w+/,
        /enum\s+\w+/,
        /var\s+\w+/,
        /let\s+\w+/,
        /print\s*\(/,
        /guard\s+let/,
        /if\s+let/,
        /for\s+\w+\s+in/,
        /switch\s+\w+\s*{/,
        /case\s+\w+:/,
        /default:/
    ],
    'kotlin': [
        /fun\s+\w+\s*\(/,
        /class\s+\w+/,
        /object\s+\w+/,
        /interface\s+\w+/,
        /val\s+\w+/,
        /var\s+\w+/,
        /println\s*\(/,
        /when\s*\(/,
        /is\s+\w+/,
        /else\s*->/,
        /for\s*\(/,
        /while\s*\(/,
        /if\s*\(/,
        /try\s*{/
    ],
    'scala': [
        /def\s+\w+\s*\(/,
        /class\s+\w+/,
        /object\s+\w+/,
        /trait\s+\w+/,
        /val\s+\w+/,
        /var\s+\w+/,
        /println\s*\(/,
        /match\s*{/,
        /case\s+\w+/,
        /=>/,
        /for\s*\(/,
        /yield/,
        /implicit\s+/,
        /lazy\s+val/
    ],
    'dart': [
        /import\s+['"]/,
        /void\s+main\s*\(/,
        /class\s+\w+/,
        /abstract\s+class/,
        /extends\s+\w+/,
        /implements\s+\w+/,
        /var\s+\w+/,
        /final\s+\w+/,
        /const\s+\w+/,
        /print\s*\(/,
        /debugPrint\s*\(/,
        /if\s*\(/,
        /for\s*\(/,
        /while\s*\(/
    ],
    'elixir': [
        /defmodule\s+\w+/,
        /def\s+\w+\s*\(/,
        /defp\s+\w+\s*\(/,
        /IO\.puts/,
        /alias\s+\w+/,
        /import\s+\w+/,
        /require\s+\w+/,
        /use\s+\w+/,
        /cond\s+do/,
        /case\s+\w+\s+do/,
        /->/,
        /|>/,
        /%{/,
        /%w\(/,
        /fn\s+\w+\s*->/,
        /with\s+\w+\s+do/,
        /for\s+\w+\s+<-/,
        /if\s+\w+\s+do/,
        /unless\s+\w+\s+do/,
        /defimpl\s+\w+/,
        /defprotocol\s+\w+/,
        /defstruct\s*\[/,
        /@type\s+\w+/,
        /@spec\s+\w+/,
        /@callback\s+\w+/,
        /@doc\s+["""]/,
        /@moduledoc\s+["""]/,
        /@external_resource/,
        /@compile\s+\w+/,
        /@deprecated/,
        /@since\s+\d+\.\d+/,
        /defmodule\s+\w+\s+do/,
        /def\s+\w+\s*\([^)]*\)\s+do/,
        /defp\s+\w+\s*\([^)]*\)\s+do/,
        /end\s*$/,
        /do\s*$/,
        /else\s+do/,
        /rescue\s+do/,
        /after\s+do/,
        /catch\s+do/,
        /defguard\s+\w+/,
        /defmacro\s+\w+/,
        /defmacrop\s+\w+/,
        /defdelegate\s+\w+/,
        /defoverridable\s+\[/,
        /defexception\s+\w+/,
        /defimpl\s+\w+\s+for\s+\w+/,
        /defprotocol\s+\w+\s+do/,
        /defcallback\s+\w+/,
        /defoptional\s+\w+/,
        /@impl\s+\w+/,
        /@derive\s+\[/,
        /@behaviour\s+\w+/,
        /@callback\s+\w+\s*\([^)]*\)\s*::/,
        /@spec\s+\w+\s*\([^)]*\)\s*::/,
        /@type\s+\w+\s*::/,
        /@opaque\s+\w+\s*::/,
        /@macrocallback\s+\w+/,
        /@optional_callbacks\s+\[/,
        /@compile\s+\w+/,
        /@external_resource\s+['"][^'"]*['"]/,
        /@deprecated\s+['"][^'"]*['"]/,
        /@since\s+\d+\.\d+\s+['"][^'"]*['"]/
    ],
    'haskell': [
        /module\s+\w+/,
        /import\s+\w+/,
        /data\s+\w+/,
        /type\s+\w+/,
        /class\s+\w+/,
        /instance\s+\w+/,
        /where/,
        /let\s+\w+/,
        /in\s+/,
        /case\s+\w+\s+of/,
        /->/,
        /::/,
        /main\s*::/,
        /putStrLn/
    ],
    'clojure': [
        /\(defn\s+\w+/,
        /\(def\s+\w+/,
        /\(ns\s+\w+/,
        /\(require\s+\[/,
        /\(import\s+\[/,
        /\(if\s+/,
        /\(when\s+/,
        /\(cond\s+/,
        /\(case\s+/,
        /\(let\s+\[/,
        /\(for\s+\[/,
        /\(map\s+/,
        /\(filter\s+/,
        /\(reduce\s+/
    ],
    'bash': [
        /^#!\/bin\/bash/,
        /^#!\/bin\/sh/,
        /echo\s+/,
        /read\s+\w+/,
        /if\s+\[/,
        /then\s*$/,
        /fi\s*$/,
        /for\s+\w+\s+in/,
        /do\s*$/,
        /done\s*$/,
        /while\s+\[/,
        /case\s+\w+\s+in/,
        /esac\s*$/,
        /function\s+\w+/
    ],
    'powershell': [
        /Write-Host/,
        /Write-Output/,
        /Get-Content/,
        /Set-Content/,
        /New-Object/,
        /$[a-zA-Z_]\w*/,
        /if\s*\(/,
        /foreach\s*\(/,
        /while\s*\(/,
        /switch\s*\(/,
        /function\s+\w+/,
        /param\s*\(/,
        /try\s*{/,
        /catch\s*{/
    ],
    'yaml': [
        /^[a-zA-Z_]\w*:/,
        /^-\s+/,
        /^#/,
        /^\s*[a-zA-Z_]\w*:\s*['"]/,
        /^\s*-\s*[a-zA-Z_]\w*:/,
        /^\s*[a-zA-Z_]\w*:\s*\|/,
        /^\s*[a-zA-Z_]\w*:\s*>/,
        /^\s*[a-zA-Z_]\w*:\s*\[/,
        /^\s*[a-zA-Z_]\w*:\s*{/,
        /^\s*[a-zA-Z_]\w*:\s*\d+$/,
        /^\s*[a-zA-Z_]\w*:\s*true$/,
        /^\s*[a-zA-Z_]\w*:\s*false$/,
        /^\s*[a-zA-Z_]\w*:\s*null$/
    ],
    'json': [
        /^\s*\{/,
        /^\s*\[/,
        /^\s*"[^"]*":\s*['"]/,
        /^\s*"[^"]*":\s*\d+/,
        /^\s*"[^"]*":\s*true/,
        /^\s*"[^"]*":\s*false/,
        /^\s*"[^"]*":\s*null/,
        /^\s*"[^"]*":\s*\{/,
        /^\s*"[^"]*":\s*\[/,
        /^\s*"[^"]*":\s*"[^"]*"$/,
        /^\s*"[^"]*":\s*\d+\.\d+$/,
        /^\s*"[^"]*":\s*"[^"]*"$/,
        /^\s*"[^"]*":\s*"[^"]*"$/,
        /^\s*"[^"]*":\s*"[^"]*"$/
    ],
    'xml': [
        /<\?xml/,
        /<!DOCTYPE/,
        /<[a-zA-Z_]\w*[^>]*>/,
        /<\/[a-zA-Z_]\w*>/,
        /<[a-zA-Z_]\w*[^>]*\/>/,
        /xmlns=/,
        /xsi:schemaLocation=/,
        /<xs:schema/,
        /<xs:element/,
        /<xs:complexType/,
        /<xs:simpleType/,
        /<xs:sequence/,
        /<xs:choice/,
        /<xs:attribute/
    ],
    'markdown': [
        /^#\s+/,
        /^##\s+/,
        /^###\s+/,
        /^####\s+/,
        /^#####\s+/,
        /^######\s+/,
        /^\*\s+/,
        /^-\s+/,
        /^\+/,
        /^\d+\.\s+/,
        /\[.*?\]\(.*?\)/,
        /!\[.*?\]\(.*?\)/,
        /^\|.*\|.*\|/,
        /^```/
    ]
};

// Веса для разных типов паттернов
const PATTERN_WEIGHTS = {
    EXACT_MATCH: 15,
    STRONG_PATTERN: 8,
    MEDIUM_PATTERN: 5,
    WEAK_PATTERN: 2
};

// Функция для анализа кода и определения языка
export function detectLanguage(code: string): ProgrammingLanguage {
    if (!code.trim()) {
        return 'php'; // По умолчанию
    }

    const scores: Record<ProgrammingLanguage, number> = {} as Record<ProgrammingLanguage, number>;
    
    // Инициализируем счетчики
    Object.keys(LANGUAGE_PATTERNS).forEach(lang => {
        scores[lang as ProgrammingLanguage] = 0;
    });

    // Анализируем код по строкам
    const lines = code.split('\n');
    
    lines.forEach(line => {
        const trimmedLine = line.trim();
        if (!trimmedLine) return;

        // Проверяем каждый язык
        Object.entries(LANGUAGE_PATTERNS).forEach(([language, patterns]) => {
            patterns.forEach(pattern => {
                if (pattern.test(trimmedLine)) {
                    // Определяем вес паттерна
                    let weight = PATTERN_WEIGHTS.MEDIUM_PATTERN;
                    
                    // Увеличиваем вес для более специфичных паттернов
                    if (pattern.source.includes('<?php') || pattern.source.includes('<!DOCTYPE')) {
                        weight = PATTERN_WEIGHTS.EXACT_MATCH;
                    } else if (pattern.source.includes('function') || pattern.source.includes('class')) {
                        weight = PATTERN_WEIGHTS.STRONG_PATTERN;
                    }
                    
                    scores[language as ProgrammingLanguage] += weight;
                }
            });
        });
    });

    // Специальная проверка для Blade синтаксиса в целом коде
    const bladePatterns = [
        /\{\{.*?\}\}/g,  // {{ }}
        /\{!!.*?!!\}/g,  // {!! !!}
        /@[a-zA-Z_]\w*\s*\(/g,  // @directive()
        /@[a-zA-Z_]\w*/g  // @directive
    ];
    
    bladePatterns.forEach(pattern => {
        const matches = code.match(pattern);
        if (matches) {
            scores['blade'] += matches.length * 10;
            scores['php-blade'] += matches.length * 12;
        }
    });

    // Специальная логика для смешанных типов
    if (scores['php'] > 0 && scores['html'] > 0) {
        scores['php-html'] += Math.max(scores['php'], scores['html']) + 10;
    }
    
    if (scores['html'] > 0 && scores['css'] > 0) {
        scores['html-css'] += Math.max(scores['html'], scores['css']) + 8;
    }
    
    if (scores['html'] > 0 && scores['javascript'] > 0) {
        scores['html-js'] += Math.max(scores['html'], scores['javascript']) + 8;
    }
    
    if (scores['php'] > 0 && scores['blade'] > 0) {
        scores['php-blade'] += Math.max(scores['php'], scores['blade']) + 15;
    }

    // Приоритет для HTML - если есть явные HTML теги, увеличиваем вес
    if (scores['html'] > 0) {
        const htmlTags = ['<!DOCTYPE', '<html', '<head', '<body', '<div', '<span', '<p', '<h1', '<h2', '<h3', '<h4', '<h5', '<h6'];
        const hasHtmlTags = htmlTags.some(tag => code.includes(tag));
        if (hasHtmlTags) {
            scores['html'] += 20; // Значительно увеличиваем вес HTML
        }
    }

    // Приоритет для Blade - если есть Blade директивы, увеличиваем вес
    if (scores['blade'] > 0) {
        const bladeDirectives = ['@if', '@foreach', '@for', '@while', '@switch', '@include', '@extends', '@section', '@yield', '@vite', '@csrf', '@config', '@app', '@env', '@lang', '@choice', '@can', '@cannot', '@hasSection', '@hasAny', '@unless', '@empty', '@endempty', '@isset', '@endisset', '@unset', '@endunset', '@production', '@endproduction', '@push', '@endpush', '@stack', '@prepend', '@endprepend', '@inject', '@component', '@endcomponent', '@slot', '@endslot', '@props', '@once', '@endonce', '@verbatim', '@endverbatim'];
        const hasBladeDirectives = bladeDirectives.some(directive => code.includes(directive));
        if (hasBladeDirectives) {
            scores['blade'] += 50; // Очень высокий приоритет для Blade
            scores['php-blade'] += 60; // Еще выше для php-blade
        }
    }

    // Приоритет для TypeScript - если есть TypeScript специфичные паттерны
    if (scores['typescript'] > 0) {
        const typescriptPatterns = ['interface', 'type', 'import type', 'export type', 'export interface', 'export enum', 'declare', 'as', 'implements', 'extends', 'readonly', 'private', 'public', 'protected', 'RouteRecordRaw', 'createApp', 'createPinia', 'createRouter', 'createWebHistory'];
        const hasTypeScriptPatterns = typescriptPatterns.some(pattern => code.includes(pattern));
        if (hasTypeScriptPatterns) {
            scores['typescript'] += 60; // Очень высокий приоритет для TypeScript
            // Уменьшаем вес Elixir если есть TypeScript паттерны
            if (scores['elixir'] > 0) {
                scores['elixir'] = Math.max(0, scores['elixir'] - 30);
            }
        }
    }

    // Специальная проверка для Vue/TypeScript кода
    if (code.includes('import') && code.includes('from') && (code.includes('vue') || code.includes('pinia') || code.includes('router'))) {
        scores['typescript'] += 50;
        scores['javascript'] += 30;
        // Уменьшаем вес Elixir для Vue/TypeScript кода
        if (scores['elixir'] > 0) {
            scores['elixir'] = Math.max(0, scores['elixir'] - 40);
        }
    }

    // Находим язык с максимальным счетом
    let bestLanguage: ProgrammingLanguage = 'php';
    let maxScore = 0;

    Object.entries(scores).forEach(([language, score]) => {
        if (score > maxScore) {
            maxScore = score;
            bestLanguage = language as ProgrammingLanguage;
        }
    });

    // Если нет явных признаков, возвращаем разумное значение по умолчанию
    if (maxScore === 0) {
        // Анализируем контекст
        if (code.includes('<') && code.includes('>')) {
            return 'html';
        } else if (code.includes('{') && code.includes('}')) {
            return 'javascript';
        } else {
            return 'php';
        }
    }

    return bestLanguage;
}

// Функция для получения уверенности в определении
export function getDetectionConfidence(code: string, detectedLanguage: ProgrammingLanguage): number {
    if (!code.trim()) return 0;

    const scores: Record<ProgrammingLanguage, number> = {} as Record<ProgrammingLanguage, number>;
    
    // Инициализируем счетчики
    Object.keys(LANGUAGE_PATTERNS).forEach(lang => {
        scores[lang as ProgrammingLanguage] = 0;
    });

    const lines = code.split('\n');
    let totalLines = 0;
    
    lines.forEach(line => {
        const trimmedLine = line.trim();
        if (!trimmedLine) return;
        totalLines++;

        Object.entries(LANGUAGE_PATTERNS).forEach(([language, patterns]) => {
            patterns.forEach(pattern => {
                if (pattern.test(trimmedLine)) {
                    scores[language as ProgrammingLanguage] += 1;
                }
            });
        });
    });

    const maxScore = Math.max(...Object.values(scores));
    const detectedScore = scores[detectedLanguage] || 0;
    
    if (maxScore === 0) return 0;
    
    // Вычисляем уверенность как процент от максимального счета
    const confidence = (detectedScore / maxScore) * 100;
    
    // Минимальная уверенность 30%, максимальная 95%
    return Math.max(30, Math.min(95, confidence));
}

// Функция для получения альтернативных языков
export function getAlternativeLanguages(code: string, detectedLanguage: ProgrammingLanguage): ProgrammingLanguage[] {
    const scores: Record<ProgrammingLanguage, number> = {} as Record<ProgrammingLanguage, number>;
    
    Object.keys(LANGUAGE_PATTERNS).forEach(lang => {
        scores[lang as ProgrammingLanguage] = 0;
    });

    const lines = code.split('\n');
    
    lines.forEach(line => {
        const trimmedLine = line.trim();
        if (!trimmedLine) return;

        Object.entries(LANGUAGE_PATTERNS).forEach(([language, patterns]) => {
            patterns.forEach(pattern => {
                if (pattern.test(trimmedLine)) {
                    scores[language as ProgrammingLanguage] += 1;
                }
            });
        });
    });

    // Сортируем языки по счету
    const sortedLanguages = Object.entries(scores)
        .filter(([lang, score]) => score > 0 && lang !== detectedLanguage)
        .sort(([, a], [, b]) => b - a)
        .slice(0, 3)
        .map(([lang]) => lang as ProgrammingLanguage);

    return sortedLanguages;
} 