# Graph Report - .  (2026-07-20)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 3072 nodes · 5823 edges · 367 communities (317 shown, 50 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 375 edges (avg confidence: 0.56)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `803ed3c2`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- jquery.dataTables.js
- jquery.min.js
- javascript.js
- codemirror.js
- Pos
- addEditorMethods
- registerEventHandlers
- raphael.js
- vim.js
- getLine
- jquery.js
- pug.js
- Chart.js
- haxe.js
- merge.js
- slim.js
- match-highlighter.js
- tern.js
- elt
- emacs.js
- search.js
- Model
- runInOp
- erlang.js
- devDependencies
- tiki.js
- stylus.js
- clike.js
- estimateHeight
- HijriService
- xquery.js
- lint.js
- markdown.js
- Line
- Kajian
- copyCursor
- xml.js
- .redraw
- updateSearchQuery
- getStateBefore
- .validateInput
- keyword2rgb
- stex.js
- Bar
- Grid
- Controller
- User.php
- TestCase
- composer.json
- scripts
- defineOptions
- AladhanProvider.php
- swift.js
- css.js
- jsx.js
- powershell.js
- context
- .to
- hwb2rgb
- closebrackets.js
- xml-fold.js
- cursorIsBefore
- verilog.js
- dataTables.select.js
- done
- KajianController
- foldgutter.js
- show-hint.js
- runmode.node.js
- julia.js
- rst.js
- morris.js
- Area
- LoginController
- bootstrap-datepicker.js
- simple.js
- Bar
- exitInsertMode
- crystal.js
- groovy.js
- python.js
- sass.js
- sql.js
- textile.js
- tiddlywiki.js
- NotificationChange.php
- bootstrap-datepicker.min.js
- javascript-hint.js
- clearInputState
- d.js
- dart.js
- ecl.js
- oz.js
- ruby.js
- ttcn.js
- vhdl.js
- AcaraController
- InformasiUmumController
- require-dev
- setup
- lab2lch
- offsetCursor
- coffeescript.js
- htmlmixed.js
- perl.js
- smarty.js
- domManip
- TempatController
- config
- bootstrap.js
- validator.min.js
- getAlpha
- panel.js
- SearchAnnotation
- mark-selection.js
- asn.1.js
- django.js
- go.js
- haskell.js
- lua.js
- nginx.js
- soy.js
- tcl.js
- ttcn-cfg.js
- vb.js
- vbscript.js
- velocity.js
- inputNumber.js
- Hover
- NarasumberController
- AppServiceProvider
- require
- UserFactory
- ScrollSpy
- lab2rgb
- placeholder.js
- closetag.js
- javascript-lint.js
- Annotation
- selection-pointer.js
- cursorEqual
- dtd.js
- fcl.js
- mirc.js
- mscgen.js
- pig.js
- r.js
- sieve.js
- solr.js
- sparql.js
- turtle.js
- yacas.js
- psr-4
- bootstrap.min.js
- matchbrackets.js
- active-line.js
- parseQuery
- commonlisp.js
- dylan.js
- eiffel.js
- octave.js
- pascal.js
- php.js
- shell.js
- morris.min.js
- informasi_umum.blade.php
- kelola_acara.blade.php
- kelola_kajian.blade.php
- kontak.blade.php
- narasumber.blade.php
- tempat.blade.php
- post-create-project-cmd
- pewaktuan_hijriah.blade.php
- bootstrap-switch.js
- bootstrap-switch.min.js
- matchtags.js
- doFold
- hardwrap.js
- fortran.js
- haml.js
- puppet.js
- jquery.scrollbar.js
- jquery.scrollbar.min.js
- admin_dashboard.blade.php
- kelola_operator.blade.php
- logo.blade.php
- PrayerTimeProviderInterface.php
- keywords
- continuecomment.js
- markdown-fold.js
- loadmode.js
- scrollpastend.js
- worker.js
- cmake.js
- tornado.js
- bootstrap-timepicker.js
- ion.rangeSlider.js
- summernote.js
- Request

## God Nodes (most connected - your core abstractions)
1. `addEditorMethods()` - 63 edges
2. `cont()` - 49 edges
3. `nextToken()` - 38 edges
4. `getLine()` - 35 edges
5. `Pos()` - 33 edges
6. `registerEventHandlers()` - 31 edges
7. `defineOptions()` - 28 edges
8. `pass()` - 27 edges
9. `Line()` - 27 edges
10. `Grid()` - 26 edges

## Surprising Connections (you probably didn't know these)
- `registerUpdate()` --indirect_call--> `change()`  [INFERRED]
  public/admin-template/libs/codemirror/addon/merge/merge.js → public/admin-template/libs/codemirror/mode/rst/rst.js
- `tokenBase()` --indirect_call--> `wordRE()`  [INFERRED]
  public/admin-template/libs/codemirror/mode/javascript/javascript.js → public/admin-template/libs/codemirror/mode/lua/lua.js
- `b()` --indirect_call--> `k()`  [INFERRED]
  public/admin-template/libs/raphael/raphael.min.js → public/admin-template/libs/chart.js/Chart.min.js
- `SearchCursor()` --indirect_call--> `match()`  [INFERRED]
  public/admin-template/libs/codemirror/addon/search/searchcursor.js → public/admin-template/libs/codemirror/addon/hint/sql-hint.js
- `Bar()` --indirect_call--> `done()`  [INFERRED]
  public/admin-template/libs/codemirror/addon/scroll/simplescrollbars.js → public/admin-template/libs/jquery/jquery.js

## Import Cycles
- None detected.

## Communities (367 total, 50 thin omitted)

### Community 0 - "jquery.dataTables.js"
Cohesion: 0.05
Nodes (85): _addNumericSort(), _fnAddColumn(), _fnAddData(), _fnAddOptionsHtml(), _fnAddTr(), _fnAdjustColumnSizing(), _fnAjaxDataSrc(), _fnAjaxParameters() (+77 more)

### Community 1 - "jquery.min.js"
Cohesion: 0.06
Nodes (60): A(), c(), d(), E(), f(), g(), h(), i() (+52 more)

### Community 2 - "javascript.js"
Cohesion: 0.10
Nodes (74): afterExport(), afterImport(), afterprop(), afterType(), arrayLiteral(), arrowBody(), arrowBodyNoComma(), block() (+66 more)

### Community 3 - "codemirror.js"
Cohesion: 0.04
Nodes (54): addClass(), alignHorizontally(), buildCollapsedSpan(), buildToken(), classTest(), clearEmptySpans(), compensateForHScroll(), createObj() (+46 more)

### Community 4 - "Pos"
Cohesion: 0.05
Nodes (69): addChangeToHistory(), addSelectionToHistory(), adjustForChange(), attachLocalSpans(), badPos(), changeEnd(), clearSelectionEvents(), clipPos() (+61 more)

### Community 5 - "addEditorMethods"
Cohesion: 0.07
Nodes (51): addEditorMethods(), addLineWidget(), addToScrollPos(), adjustScrollWhenAboveVisible(), calculateScrollPos(), charCoords(), charWidth(), clipLine() (+43 more)

### Community 6 - "registerEventHandlers"
Cohesion: 0.08
Nodes (52): activeElt(), bind(), clearDragCursor(), clickInGutter(), CodeMirror(), contains(), contextMenuInGutter(), delayBlurEvent() (+44 more)

### Community 7 - "raphael.js"
Cohesion: 0.05
Nodes (33): RFC-4122, Animation(), base3(), bezlen(), CubicBezierAtTime(), getTatLen(), inter(), interCount() (+25 more)

### Community 8 - "vim.js"
Cohesion: 0.04
Nodes (23): charIdxInLine(), cmKey(), cmKeyToVimKey(), commandMatch(), commandMatches(), isNumber(), isUpperCase(), moveToCharacter() (+15 more)

### Community 9 - "getLine"
Cohesion: 0.09
Nodes (44): bidiLeft(), bidiRight(), buildLineContent(), buildTokenBadBidi(), changeLine(), collapsedSpanAtEnd(), collapsedSpanAtSide(), collapsedSpanAtStart() (+36 more)

### Community 10 - "jquery.js"
Cohesion: 0.05
Nodes (11): augmentWidthOrHeight(), curCSS(), dataAttr(), getData(), getWidthOrHeight(), Identity(), NOTE: This can be skipped if there are no unmatched elements (i.e., `matchedCoun, TODO: Now that all calls to _data and _removeData have been replaced (+3 more)

### Community 11 - "pug.js"
Cohesion: 0.09
Nodes (42): append(), attributesBlock(), attrs(), attrsContinued(), block(), call(), callArguments(), caseStatement() (+34 more)

### Community 12 - "Chart.js"
Cohesion: 0.05
Nodes (18): acquireContext(), getBarBounds(), getConstraintDimension(), hexDouble(), hexString(), hslaString(), hslString(), initCanvas() (+10 more)

### Community 13 - "haxe.js"
Cohesion: 0.15
Nodes (42): block(), chain(), commasep(), cont(), expect(), expression(), forin(), forspec1() (+34 more)

### Community 14 - "merge.js"
Cohesion: 0.10
Nodes (38): alignChunks(), alignLines(), attrs(), buildGap(), chunkBoundariesAround(), clear(), clearMarks(), collapseIdenticalStretches() (+30 more)

### Community 15 - "slim.js"
Cohesion: 0.12
Nodes (35): attributeWrapper(), attributeWrapperAssign(), attributeWrapperValue(), backup(), commaContinuable(), comment(), commentMode(), continueLine() (+27 more)

### Community 16 - "match-highlighter.js"
Cohesion: 0.08
Nodes (25): addOverlay(), boundariesAround(), cursorActivity(), highlightMatches(), isWord(), makeOverlay(), onFocus(), removeOverlay() (+17 more)

### Community 17 - "tern.js"
Cohesion: 0.13
Nodes (31): applyChanges(), atInterestingExpression(), buildRequest(), closeArgHints(), dialog(), docValue(), elt(), fadeOut() (+23 more)

### Community 18 - "elt"
Cohesion: 0.11
Nodes (31): buildLineElement(), countDirtyView(), Display(), elt(), endOperation_W1(), ensureLineWrapped(), findPosV(), getLineContent() (+23 more)

### Community 19 - "emacs.js"
Cohesion: 0.10
Nodes (22): addPrefix(), addPrefixMap(), addToRing(), byExpr(), clearMark(), clearPrefix(), findEnd(), getFromRing() (+14 more)

### Community 20 - "search.js"
Cohesion: 0.15
Nodes (28): addMatches(), cleanName(), eachWord(), findTableByAlias(), getTable(), getText(), insertBackticks(), isArray() (+20 more)

### Community 21 - "Model"
Cohesion: 0.16
Nodes (6): Acara, InformasiUmum, DatabaseSeeder, Model, Seeder, WithoutModelEvents

### Community 22 - "runInOp"
Cohesion: 0.11
Nodes (12): applyTextInput(), docMethodOp(), endOperation(), fireOrphanDelayed(), handlePaste(), methodOp(), pushOperation(), runInOp() (+4 more)

### Community 23 - "erlang.js"
Cohesion: 0.20
Nodes (24): aToken(), d(), defaultToken(), doubleQuote(), fakeToken(), getToken(), getTokenIndex(), greedy() (+16 more)

### Community 24 - "devDependencies"
Cohesion: 0.08
Nodes (23): axios, concurrently, laravel-echo, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-echo (+15 more)

### Community 25 - "tiki.js"
Cohesion: 0.17
Nodes (21): getTargetFromTrigger(), getIntersectItems(), getNearestItems(), indexMode(), parseVisibleItems(), attributes(), attvalue(), attvaluemaybe() (+13 more)

### Community 26 - "stylus.js"
Cohesion: 0.12
Nodes (14): endOfLine(), escapeRegExp(), pass(), popAndPass(), startOfLine(), tokenBase(), tokenCComment(), tokenParenthesized() (+6 more)

### Community 27 - "clike.js"
Cohesion: 0.11
Nodes (10): contains(), cpp11StringHook(), isTopScope(), maybeEOL(), tokenBase(), tokenComment(), tokenRawString(), tokenString() (+2 more)

### Community 28 - "estimateHeight"
Cohesion: 0.25
Nodes (7): addMarkedSpan(), attachMarkedSpans(), cleanUpLine(), detachMarkedSpans(), estimateHeight(), Line(), updateLine()

### Community 29 - "HijriService"
Cohesion: 0.22
Nodes (6): HijriTickerController, HijriSetting, HijriService, Carbon, Carbon, Request

### Community 30 - "xquery.js"
Cohesion: 0.25
Nodes (17): chain(), isEQNameAhead(), isIn(), isInString(), isInXmlAttributeBlock(), isInXmlBlock(), isInXmlConstructor(), popStateStack() (+9 more)

### Community 31 - "lint.js"
Cohesion: 0.20
Nodes (16): annotationTooltip(), clearMarks(), getMaxSeverity(), groupByLine(), hideTooltip(), lintAsync(), LintState(), makeMarker() (+8 more)

### Community 32 - "markdown.js"
Cohesion: 0.21
Nodes (15): blockNormal(), footnoteLink(), footnoteLinkInside(), getLinkHrefInside(), getMode(), getType(), handleText(), htmlBlock() (+7 more)

### Community 34 - "Kajian"
Cohesion: 0.15
Nodes (5): admin_dashboard_Controller, Kajian, Kontak, Narasumber, Tempat

### Community 35 - "copyCursor"
Cohesion: 0.14
Nodes (16): clipCursorToContent(), clipToLine(), copyCursor(), exitVisualMode(), extendLineToColumn(), findBeginningAndEnd(), findSymbol(), findWord() (+8 more)

### Community 36 - "xml.js"
Cohesion: 0.18
Nodes (14): attrContinuedState(), attrEqState(), attrState(), attrValueState(), closeState(), closeStateErr(), closeTagNameState(), doctype() (+6 more)

### Community 39 - "updateSearchQuery"
Cohesion: 0.15
Nodes (17): clearSearchHighlight(), dialog(), doReplace(), findNext(), getSearchState(), handleQuery(), highlightSearchMatches(), logSearchQuery() (+9 more)

### Community 40 - "getStateBefore"
Cohesion: 0.20
Nodes (17): callBlankLine(), copyState(), countColumn(), extractLineClasses(), findStartLine(), getLineStyles(), getStateBefore(), highlightLine() (+9 more)

### Community 43 - "keyword2rgb"
Cohesion: 0.15
Nodes (17): cmyk2hsl(), cmyk2hsv(), cmyk2hwb(), cmyk2keyword(), cmyk2rgb(), hwb2hsv(), keyword2hsl(), keyword2hsv() (+9 more)

### Community 44 - "stex.js"
Cohesion: 0.26
Nodes (13): ncomment(), normal(), stringGap(), stringLiteral(), switchState(), beginParams(), getMostPowerful(), inMathMode() (+5 more)

### Community 47 - "Controller"
Cohesion: 0.20
Nodes (6): KontakController, Request, Request, VmixDataController, Controller, HijriService

### Community 48 - "User.php"
Cohesion: 0.27
Nodes (6): OperatorController, Request, User, Authenticatable, HasFactory, Notifiable

### Community 49 - "TestCase"
Cohesion: 0.18
Nodes (5): BaseTestCase, ExampleTest, TestCase, ExampleTest, HijriServiceTest

### Community 50 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, extra, laravel, dont-discover, license, minimum-stability (+5 more)

### Community 51 - "scripts"
Cohesion: 0.14
Nodes (14): scripts, dev, post-autoload-dump, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+6 more)

### Community 52 - "defineOptions"
Cohesion: 0.09
Nodes (22): adjustView(), attachDoc(), buildViewArray(), clearCaches(), clearLineMeasurementCache(), clearLineMeasurementCacheFor(), defaultSpecialCharPlaceholder(), defineOptions() (+14 more)

### Community 53 - "AladhanProvider.php"
Cohesion: 0.19
Nodes (7): AladhanProvider, Carbon, AlhabibProvider, Carbon, MyQuranProvider, Carbon, PrayerTimeProviderInterface

### Community 54 - "swift.js"
Cohesion: 0.21
Nodes (5): identifier(), tokenBase(), tokenComment(), tokenString(), tokenUntilClosingParen()

### Community 55 - "css.js"
Cohesion: 0.23
Nodes (6): pass(), popAndPass(), ret(), tokenBase(), tokenParenthesized(), tokenString()

### Community 56 - "jsx.js"
Cohesion: 0.22
Nodes (4): flatXMLIndent(), jsToken(), token(), xmlToken()

### Community 57 - "powershell.js"
Cohesion: 0.29
Nodes (11): tokenBase(), tokenComment(), tokenDoubleQuoteString(), tokenHereStringInterpolation(), tokenInterpolation(), tokenMultiString(), tokenMultiStringReturn(), tokenSingleQuoteString() (+3 more)

### Community 58 - "context"
Cohesion: 0.23
Nodes (13): context(), addCombinator(), condense(), createPositionalPseudo(), elementMatcher(), markFunction(), matcherFromGroupMatchers(), matcherFromTokens() (+5 more)

### Community 60 - "hwb2rgb"
Cohesion: 0.15
Nodes (15): hsl2cmyk(), hsl2hwb(), hsl2keyword(), hsl2rgb(), hsv2cmyk(), hsv2hwb(), hsv2keyword(), hsv2rgb() (+7 more)

### Community 61 - "closebrackets.js"
Cohesion: 0.36
Nodes (11): charsAround(), contractSelection(), enteringString(), getConfig(), getOption(), handleBackspace(), handleChar(), handleEnter() (+3 more)

### Community 62 - "xml-fold.js"
Cohesion: 0.35
Nodes (9): findMatchingClose(), findMatchingOpen(), nextLine(), prevLine(), tagAt(), toNextTag(), toPrevTag(), toTagEnd() (+1 more)

### Community 63 - "cursorIsBefore"
Cohesion: 0.23
Nodes (12): cursorIsBefore(), cursorIsBetween(), cursorMax(), cursorMin(), expandSelection(), expandWordUnderCursor(), getHead(), getSelectedAreaRange() (+4 more)

### Community 64 - "verilog.js"
Cohesion: 0.20
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 65 - "dataTables.select.js"
Cohesion: 0.29
Nodes (10): cellRange(), clear(), disableMouseSelection(), enableMouseSelection(), eventTrigger(), i18n(), info(), init() (+2 more)

### Community 66 - "done"
Cohesion: 0.18
Nodes (12): adoptValue(), ajaxConvert(), ajaxHandleResponses(), Animation(), createFxNow(), createTween(), defaultPrefilter(), done() (+4 more)

### Community 68 - "foldgutter.js"
Cohesion: 0.33
Nodes (8): isFolded(), marker(), onChange(), onFold(), onGutterClick(), onViewportChange(), updateFoldInfo(), updateInViewport()

### Community 69 - "show-hint.js"
Cohesion: 0.25
Nodes (6): applicableHelpers(), buildKeyMap(), getHintElement(), getText(), resolveAutoHints(), Widget()

### Community 70 - "runmode.node.js"
Cohesion: 0.27
Nodes (7): copyObj(), extendMode(), getMode(), resolveMode(), runMode(), splitLines(), startState()

### Community 71 - "julia.js"
Cohesion: 0.33
Nodes (6): callOrDef(), currentScope(), inArray(), inGenerator(), tokenBase(), tokenStringFactory()

### Community 72 - "rst.js"
Cohesion: 0.47
Nodes (9): as_block(), change(), phase(), stage(), to_comment(), to_explicit(), to_mode(), to_normal() (+1 more)

### Community 75 - "LoginController"
Cohesion: 0.24
Nodes (4): Controller, LoginController, Request, MainpageController

### Community 77 - "simple.js"
Cohesion: 0.36
Nodes (9): asToken(), cmp(), ensureState(), enterLocalMode(), indentFunction(), indexOf(), Rule(), tokenFunction() (+1 more)

### Community 79 - "exitInsertMode"
Cohesion: 0.33
Nodes (6): executeMacroRegister(), exitInsertMode(), logInsertModeChange(), onChange(), onKeyEventTargetKeyDown(), repeatLastEdit()

### Community 80 - "crystal.js"
Cohesion: 0.33
Nodes (5): chain(), tokenBase(), tokenMacro(), tokenNest(), tokenQuote()

### Community 81 - "groovy.js"
Cohesion: 0.27
Nodes (4): expectExpression(), startString(), tokenBase(), tokenComment()

### Community 82 - "python.js"
Cohesion: 0.42
Nodes (8): dedent(), pushBracketScope(), pushPyScope(), tokenBase(), tokenBaseInner(), tokenLexer(), tokenStringFactory(), top()

### Community 83 - "sass.js"
Cohesion: 0.36
Nodes (8): buildInterpolationTokenizer(), buildStringTokenizer(), comment(), dedent(), indent(), tokenBase(), tokenLexer(), urlTokens()

### Community 85 - "textile.js"
Cohesion: 0.40
Nodes (8): activeStyles(), handlePhraseModifier(), RE(), startNewLine(), textileDisabled(), togglePhraseModifier(), tokenStyles(), tokenStylesWith()

### Community 86 - "tiddlywiki.js"
Cohesion: 0.38
Nodes (9): chain(), tokenBase(), twTokenCode(), twTokenComment(), twTokenEm(), twTokenMacro(), twTokenStrike(), twTokenStrong() (+1 more)

### Community 87 - "NotificationChange.php"
Cohesion: 0.33
Nodes (5): NotificationChange, Dispatchable, InteractsWithSockets, SerializesModels, ShouldBroadcast

### Community 88 - "bootstrap-datepicker.min.js"
Cohesion: 0.31
Nodes (4): c(), d(), h(), i()

### Community 90 - "javascript-hint.js"
Cohesion: 0.39
Nodes (7): coffeescriptHint(), forAllProps(), forEach(), getCoffeeScriptToken(), getCompletions(), javascriptHint(), scriptHint()

### Community 91 - "clearInputState"
Cohesion: 0.31
Nodes (7): clearInputState(), handleEsc(), handleKeyInsertMode(), handleKeyNonInsertMode(), handleMacroRecording(), InputState(), logKey()

### Community 92 - "d.js"
Cohesion: 0.31
Nodes (4): tokenBase(), tokenComment(), tokenNestedComment(), tokenString()

### Community 94 - "ecl.js"
Cohesion: 0.28
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 96 - "oz.js"
Cohesion: 0.28
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 98 - "ruby.js"
Cohesion: 0.44
Nodes (7): chain(), readBlockComment(), readHereDoc(), readQuoted(), tokenBase(), tokenBaseOnce(), tokenBaseUntilBrace()

### Community 100 - "ttcn.js"
Cohesion: 0.28
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 101 - "vhdl.js"
Cohesion: 0.28
Nodes (3): tokenBase(), tokenString(), tokenString2()

### Community 104 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 105 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 106 - "lab2lch"
Cohesion: 0.50
Nodes (4): lab2lch(), rgb2lch(), xyz2lab(), xyz2lch()

### Community 107 - "offsetCursor"
Cohesion: 0.19
Nodes (14): attachVimMap(), defineOption(), detachVimMap(), enterVimMode(), getOffset(), getOnPasteFn(), leaveVimMode(), maybeInitVimState() (+6 more)

### Community 108 - "coffeescript.js"
Cohesion: 0.39
Nodes (5): dedent(), indent(), tokenBase(), tokenFactory(), tokenLexer()

### Community 109 - "htmlmixed.js"
Cohesion: 0.43
Nodes (6): findMatchingMode(), getAttrRegexp(), getAttrValue(), getTagRegexp(), html(), maybeBackup()

### Community 110 - "perl.js"
Cohesion: 0.46
Nodes (7): eatSuffix(), look(), prefix(), suffix(), tokenChain(), tokenPerl(), tokenSOMETHING()

### Community 111 - "smarty.js"
Cohesion: 0.43
Nodes (7): chain(), cont(), doesNotCount(), tokenAttribute(), tokenBlock(), tokenSmarty(), tokenTop()

### Community 112 - "domManip"
Cohesion: 0.32
Nodes (8): buildFragment(), disableScript(), DOMEval(), domManip(), getAll(), remove(), restoreScript(), setGlobalEval()

### Community 114 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 115 - "bootstrap.js"
Cohesion: 0.38
Nodes (3): clearMenus(), getParent(), NOTE: POPOVER EXTENDS tooltip.js

### Community 116 - "validator.min.js"
Cohesion: 0.52
Nodes (6): b(), c(), d(), e(), f(), g()

### Community 117 - "getAlpha"
Cohesion: 0.38
Nodes (7): getAlpha(), getHsl(), getHsla(), getHwb(), getRgb(), getRgba(), scale()

### Community 118 - "panel.js"
Cohesion: 0.38
Nodes (3): isAtTop(), Panel(), removePanels()

### Community 120 - "mark-selection.js"
Cohesion: 0.62
Nodes (6): clear(), coverRange(), onChange(), onCursorActivity(), reset(), update()

### Community 123 - "django.js"
Cohesion: 0.38
Nodes (3): inString(), inTag(), inVariable()

### Community 124 - "go.js"
Cohesion: 0.38
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 125 - "haskell.js"
Cohesion: 0.57
Nodes (5): ncomment(), normal(), stringGap(), stringLiteral(), switchState()

### Community 126 - "lua.js"
Cohesion: 0.43
Nodes (5): bracketed(), normal(), readBracket(), string(), wordRE()

### Community 127 - "nginx.js"
Cohesion: 0.62
Nodes (5): ret(), tokenBase(), tokenCComment(), tokenSGMLComment(), tokenString()

### Community 129 - "tcl.js"
Cohesion: 0.48
Nodes (5): chain(), tokenBase(), tokenComment(), tokenString(), tokenUnparsed()

### Community 131 - "vb.js"
Cohesion: 0.52
Nodes (5): dedent(), indent(), tokenBase(), tokenLexer(), tokenStringFactory()

### Community 132 - "vbscript.js"
Cohesion: 0.43
Nodes (4): dedent(), indent(), tokenBase(), tokenStringFactory()

### Community 133 - "velocity.js"
Cohesion: 0.48
Nodes (5): chain(), tokenBase(), tokenComment(), tokenString(), tokenUnparsed()

### Community 134 - "inputNumber.js"
Cohesion: 0.48
Nodes (4): changeInputsVal(), checkInputAttr(), lessValFn(), moreValFn()

### Community 138 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/framework, laravel/reverb, laravel/tinker, livewire/livewire, php

### Community 139 - "UserFactory"
Cohesion: 0.47
Nodes (3): UserFactory, Factory, static

### Community 141 - "lab2rgb"
Cohesion: 0.40
Nodes (6): lab2rgb(), lab2xyz(), lch2lab(), lch2rgb(), lch2xyz(), xyz2rgb()

### Community 142 - "placeholder.js"
Cohesion: 0.73
Nodes (5): clearPlaceholder(), isEmpty(), onBlur(), onChange(), setPlaceholder()

### Community 143 - "closetag.js"
Cohesion: 0.60
Nodes (5): autoCloseCurrent(), autoCloseGT(), autoCloseSlash(), closingTagExists(), indexOf()

### Community 144 - "javascript-lint.js"
Cohesion: 0.60
Nodes (5): cleanup(), fixWith(), isBogus(), parseErrors(), validator()

### Community 146 - "selection-pointer.js"
Cohesion: 0.60
Nodes (5): mousemove(), mouseout(), reset(), scheduleUpdate(), update()

### Community 147 - "cursorEqual"
Cohesion: 0.47
Nodes (6): add(), cursorEqual(), getIndex(), move(), recordJumpPosition(), selectBlock()

### Community 148 - "dtd.js"
Cohesion: 0.73
Nodes (5): inBlock(), ret(), tokenBase(), tokenSGMLComment(), tokenString()

### Community 150 - "mirc.js"
Cohesion: 0.53
Nodes (4): chain(), tokenBase(), tokenComment(), tokenUnparsed()

### Community 151 - "mscgen.js"
Cohesion: 0.47
Nodes (3): produceTokenFunction(), wordRegexp(), wordRegexpBoundary()

### Community 152 - "pig.js"
Cohesion: 0.53
Nodes (4): chain(), tokenBase(), tokenComment(), tokenString()

### Community 154 - "sieve.js"
Cohesion: 0.47
Nodes (3): tokenBase(), tokenCComment(), tokenString()

### Community 155 - "solr.js"
Cohesion: 0.60
Nodes (5): isNumber(), tokenBase(), tokenOperator(), tokenString(), tokenWord()

### Community 159 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 160 - "bootstrap.min.js"
Cohesion: 0.90
Nodes (4): b(), c(), d(), f()

### Community 162 - "matchbrackets.js"
Cohesion: 0.70
Nodes (4): doMatchBrackets(), findMatchingBracket(), matchBrackets(), scanForBracket()

### Community 163 - "active-line.js"
Cohesion: 0.70
Nodes (4): clearActiveLines(), sameArray(), selectionChange(), updateActiveLines()

### Community 164 - "parseQuery"
Cohesion: 0.40
Nodes (5): findUnescapedSlashes(), getOption(), parseQuery(), splitBySlash(), translateRegex()

### Community 166 - "dylan.js"
Cohesion: 0.70
Nodes (4): chain(), tokenBase(), tokenComment(), tokenString()

### Community 167 - "eiffel.js"
Cohesion: 0.60
Nodes (3): chain(), readQuoted(), tokenBase()

### Community 169 - "octave.js"
Cohesion: 0.60
Nodes (3): tokenBase(), tokenTranspose(), wordRegexp()

### Community 170 - "pascal.js"
Cohesion: 0.60
Nodes (3): tokenBase(), tokenComment(), tokenString()

### Community 172 - "shell.js"
Cohesion: 0.60
Nodes (3): tokenBase(), tokenize(), tokenString()

### Community 173 - "morris.min.js"
Cohesion: 0.70
Nodes (4): a(), b(), c(), d()

### Community 174 - "informasi_umum.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 175 - "kelola_acara.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 176 - "kelola_kajian.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 177 - "kontak.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 178 - "narasumber.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 179 - "tempat.blade.php"
Cohesion: 0.40
Nodes (4): partials.assets, partials.metadata, partials.navbar, partials.sidebar

### Community 180 - "post-create-project-cmd"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 181 - "pewaktuan_hijriah.blade.php"
Cohesion: 0.50
Nodes (3): partials.assets, partials.navbar, partials.sidebar

### Community 184 - "bootstrap-switch.min.js"
Cohesion: 0.83
Nodes (3): a(), _classCallCheck(), d()

### Community 186 - "matchtags.js"
Cohesion: 0.83
Nodes (3): clear(), doMatchTags(), maybeUpdateMatch()

### Community 188 - "doFold"
Cohesion: 1.00
Nodes (3): doFold(), getOption(), makeWidget()

### Community 191 - "haml.js"
Cohesion: 0.83
Nodes (3): html(), ruby(), rubyInQuote()

### Community 197 - "admin_dashboard.blade.php"
Cohesion: 0.50
Nodes (3): partials.assets, partials.navbar, partials.sidebar

### Community 198 - "kelola_operator.blade.php"
Cohesion: 0.50
Nodes (3): partials.assets, partials.navbar, partials.sidebar

### Community 199 - "logo.blade.php"
Cohesion: 0.50
Nodes (3): partials.assets, partials.navbar, partials.sidebar

### Community 201 - "keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **97 isolated node(s):** `RFC-4122`, `partials.assets`, `partials.navbar`, `partials.sidebar`, `partials.metadata` (+92 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **50 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `getTargetFromTrigger()` connect `tiki.js` to `bootstrap.js`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Why does `Plugin()` connect `.to` to `.toggle`, `bootstrap.js`?**
  _High betweenness centrality (0.054) - this node is a cross-community bridge._
- **Are the 8 inferred relationships involving `addEditorMethods()` (e.g. with `.lineNo()` and `map()`) actually correct?**
  _`addEditorMethods()` has 8 INFERRED edges - model-reasoned connections that need verification._
- **What connects `RFC-4122`, `partials.assets`, `partials.navbar` to the rest of the system?**
  _97 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `jquery.dataTables.js` be split into smaller, more focused modules?**
  _Cohesion score 0.0528555431131019 - nodes in this community are weakly interconnected._
- **Should `jquery.min.js` be split into smaller, more focused modules?**
  _Cohesion score 0.05886075949367089 - nodes in this community are weakly interconnected._
- **Should `javascript.js` be split into smaller, more focused modules?**
  _Cohesion score 0.1048951048951049 - nodes in this community are weakly interconnected._