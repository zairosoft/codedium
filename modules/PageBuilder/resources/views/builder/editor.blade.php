<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Builder - {{ $page->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Third-party CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Page Builder CSS -->
    <link href="{{ asset('modules/pagebuilder/vvvebjs/css/vvvebjs.min.css') }}" rel="stylesheet">
    <link href="{{ asset('modules/pagebuilder/vvvebjs/css/fonts.css') }}" rel="stylesheet">
    
    <!-- jQuery first to make sure it's available for our patches -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- VvvebJs patcher script - must run before any VvvebJs code -->
    <script>
        // Create dummy Vvveb object that will be extended by the actual library
        window.Vvveb = window.Vvveb || {};
        window.Vvveb.Builder = window.Vvveb.Builder || {};
        
        // Store the original init function (if it exists already)
        window.Vvveb.Builder._originalInit = window.Vvveb.Builder.init;
        
        // Replace with our safe version
        window.Vvveb.Builder.init = function(id, callback) {
            console.log("Safe VvvebJs init running for:", id);
            
            // Get the iframe element
            var iframe = document.getElementById(id);
            if (!iframe) {
                console.error("Iframe element not found:", id);
                return;
            }
            
            // Store basic properties
            this.iframe = iframe;
            this.id = id;
            
            // Set iframe source if not already set
            if (!iframe.src) {
                iframe.src = iframe.getAttribute("data-src") || "";
            }
            
            // Create minimal initialization (skipping _initBox entirely)
            var self = this;
            iframe.addEventListener("load", function() {
                console.log("Iframe loaded in safe initialization");
                
                try {
                    // Set up basic frame references
                    self.frameWindow = iframe.contentWindow;
                    self.frameDoc = iframe.contentWindow.document;
                    self.frameHtml = self.frameDoc.documentElement;
                    self.frameHead = self.frameDoc.head;
                    self.frameBody = self.frameDoc.body;
                    
                    // Hide loading message if exists
                    var loadingMessage = document.querySelector(".loading-message");
                    if (loadingMessage) {
                        loadingMessage.classList.remove("active");
                    }
                    
                    // Define minimal component rendering if not already defined
                    if (!Vvveb.Components) {
                        Vvveb.Components = {
                            componentPropertiesElement: ".properties-tabs-content .tab-pane",
                            
                            render: function(component) {
                                console.log("Rendering component:", component);
                            }
                        };
                    }
                    
                    // Define minimal GUI if not already defined
                    if (!self.Gui) {
                        self.Gui = {
                            init: function() {
                                console.log("Minimal GUI initialized");
                            }
                        };
                    }
                    
                    // Call the callback if provided
                    if (typeof callback === 'function') {
                        setTimeout(function() {
                            try {
                                callback();
                            } catch (e) {
                                console.error("Error in iframe callback:", e);
                            }
                        }, 100);
                    }
                } catch (e) {
                    console.error("Error in safe iframe load handler:", e);
                }
            });
            
            console.log("Safe initialization setup complete");
        };
        
        // Provide safe methods for all commonly used functions
        window.Vvveb.Builder.setHtml = window.Vvveb.Builder.setHtml || function(html) {
            console.log("Setting HTML content");
            if (this.frameBody) {
                this.frameBody.innerHTML = html;
            }
        };
        
        window.Vvveb.Builder.getHtml = window.Vvveb.Builder.getHtml || function() {
            console.log("Getting HTML content");
            return this.frameBody ? this.frameBody.innerHTML : "";
        };
        
        window.Vvveb.Builder.getCss = window.Vvveb.Builder.getCss || function() {
            console.log("Getting CSS content");
            return "";
        };
        
        // Define minimal Components namespace to avoid errors
        window.Vvveb.Components = window.Vvveb.Components || {
            get: function(component) {
                return null;
            },
            
            render: function(component) {
                console.log("Minimal component rendering for:", component);
            }
        };
        
        console.log("Safe VvvebJs builder defined and ready");
    </script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Vvveb CSS -->
    <link href="{{ asset('modules/pagebuilder/vvvebjs/css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('modules/pagebuilder/vvvebjs/css/vvvebjs-editor-helpers.css') }}" rel="stylesheet">
    
    <!-- Additional libraries that might be needed -->
    <link href="{{ asset('modules/pagebuilder/vvvebjs/libs/codemirror/lib/codemirror.css') }}" rel="stylesheet">
    <link href="{{ asset('modules/pagebuilder/vvvebjs/libs/codemirror/theme/material.css') }}" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        #vvveb-builder {
            height: 100vh;
            width: 100%;
            position: relative;
        }
        
        /* Custom styles to replace missing inputs.css */
        .component-properties input[type=text],
        .component-properties input[type=number],
        .component-properties input[type=url],
        .component-properties input[type=checkbox],
        .component-properties input[type=color] {
            width: 100%;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            padding: 5px;
        }
        
        .component-properties select {
            width: 100%;
            border: 1px solid #ddd;
            padding: 5px;
            margin-bottom: 5px;
        }
        
        .component-properties label {
            font-size: 0.9rem;
            margin-bottom: 5px;
            display: block;
        }
        
        .component-properties .form-group {
            margin-bottom: 15px;
        }
        
        /* Add section button and box styles */
        #add-section-btn {
            position: absolute;
            display: none;
            z-index: 100;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 30px;
            text-align: center;
        }
        
        #add-section-box {
            position: absolute;
            z-index: 999;
            width: 600px;
            max-width: 90vw;
            max-height: 70vh;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        #add-section-box .header {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        
        #add-section-box .section-box-actions {
            display: flex;
            align-items: center;
        }
        
        #add-section-box .search {
            padding: 10px;
            position: relative;
        }
        
        #add-section-box .search .clear-backspace {
            position: absolute;
            right: 20px;
            top: 15px;
            background: none;
            border: none;
            color: #999;
        }
        
        #add-section-box .sections-list,
        #add-section-box .blocks-list {
            padding: 10px;
            overflow-y: auto;
            max-height: 50vh;
        }
        
        /* Text editor toolbar styles */
        #text-editor-toolbar {
            display: none;
            margin-top: 5px;
        }
        
        #text-editor-toolbar .form-select {
            height: 31px;
            padding: 2px 5px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div id="vvveb-builder">
        <div id="top-panel">
            <div class="btn-group float-start" role="group">
                <a class="btn btn-light" href="{{ route('pagebuilder.index') }}">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            
            <div class="btn-group me-3" role="group">
                <button class="btn btn-light" id="undo-btn" data-vvveb-action="undo" data-vvveb-shortcut="ctrl+z">
                    <i class="fas fa-undo"></i> Undo
                </button>
                <button class="btn btn-light" id="redo-btn" data-vvveb-action="redo" data-vvveb-shortcut="ctrl+shift+z">
                    <i class="fas fa-redo"></i> Redo
                </button>
            </div>
            
            <div class="btn-group me-3" role="group">
                <button class="btn btn-primary" id="save-btn" data-vvveb-action="saveAjax">
                    <i class="fas fa-save"></i> Save
                </button>
                <a class="btn btn-success" target="_blank" href="{{ route('pagebuilder.preview', $page->slug) }}">
                    <i class="fas fa-eye"></i> Preview
                </a>
            </div>
            
            <!-- Loading message that VvvebJs tries to access -->
            <div class="loading-message active">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </div>
            
            <!-- Text formatting toolbar -->
            <div class="btn-group me-3 float-end" role="group" id="text-editor-toolbar" style="display: none;">
                <button class="btn btn-light" id="bold-btn" title="Bold">
                    <i class="fas fa-bold"></i>
                </button>
                <button class="btn btn-light" id="italic-btn" title="Italic">
                    <i class="fas fa-italic"></i>
                </button>
                <button class="btn btn-light" id="underline-btn" title="Underline">
                    <i class="fas fa-underline"></i>
                </button>
                <button class="btn btn-light" id="strike-btn" title="Strike">
                    <i class="fas fa-strikethrough"></i>
                </button>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-light dropdown-toggle" type="button" id="heading-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-heading"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="heading-btn">
                        <li><a class="dropdown-item" href="#" id="h1-btn">Heading 1</a></li>
                        <li><a class="dropdown-item" href="#" id="h2-btn">Heading 2</a></li>
                        <li><a class="dropdown-item" href="#" id="h3-btn">Heading 3</a></li>
                        <li><a class="dropdown-item" href="#" id="h4-btn">Heading 4</a></li>
                        <li><a class="dropdown-item" href="#" id="h5-btn">Heading 5</a></li>
                        <li><a class="dropdown-item" href="#" id="h6-btn">Heading 6</a></li>
                    </ul>
                </div>
                <button class="btn btn-light" id="link-btn" title="Insert Link">
                    <i class="fas fa-link"></i>
                </button>
                <button class="btn btn-light" id="forecolor-btn" title="Text Color">
                    <i class="fas fa-paint-brush"></i>
                </button>
                <button class="btn btn-light" id="backcolor-btn" title="Background Color">
                    <i class="fas fa-fill-drip"></i>
                </button>
                <!-- Color inputs (hidden but used by the builder) -->
                <input type="color" id="fore-color" class="d-none">
                <input type="color" id="back-color" class="d-none">
                
                <!-- Font size and family dropdowns -->
                <select id="font-size" class="form-select form-select-sm d-inline-block ms-1" style="width: 100px;">
                    <option value=""> - Font size - </option>
                    <!-- Options will be populated by JavaScript -->
                </select>
                
                <select id="font-family" class="form-select form-select-sm d-inline-block ms-1" style="width: 120px;">
                    <option value=""> - Font family - </option>
                    <option value="Arial" data-provider="system">Arial</option>
                    <option value="Helvetica" data-provider="system">Helvetica</option>
                    <option value="Times New Roman" data-provider="system">Times New Roman</option>
                    <option value="Courier New" data-provider="system">Courier New</option>
                    <option value="Verdana" data-provider="system">Verdana</option>
                    <option value="Georgia" data-provider="system">Georgia</option>
                </select>
                
                <!-- Text alignment buttons -->
                <div class="btn-group ms-1" role="group">
                    <button class="btn btn-light" id="justify-btn" data-value="left" title="Align Left">
                        <i class="fas fa-align-left"></i>
                    </button>
                    <button class="btn btn-light" id="justify-btn" data-value="center" title="Align Center">
                        <i class="fas fa-align-center"></i>
                    </button>
                    <button class="btn btn-light" id="justify-btn" data-value="right" title="Align Right">
                        <i class="fas fa-align-right"></i>
                    </button>
                    <button class="btn btn-light" id="justify-btn" data-value="justify" title="Justify">
                        <i class="fas fa-align-justify"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="left-panel">
            <div id="filemanager">
                <div class="header">
                    <a href="#" class="text-secondary">Components</a>
                </div>
                
                <!-- Component tabs -->
                <ul class="nav nav-tabs nav-fill" id="components-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="components-tab" data-bs-toggle="tab" data-bs-target="#components-tab-pane" type="button" role="tab" aria-controls="components-tab-pane" aria-selected="true">Components</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections-tab-pane" type="button" role="tab" aria-controls="sections-tab-pane" aria-selected="false">Sections</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="blocks-tab" data-bs-toggle="tab" data-bs-target="#blocks-tab-pane" type="button" role="tab" aria-controls="blocks-tab-pane" aria-selected="false">Blocks</button>
                    </li>
                </ul>
                
                <!-- Tab content for components -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="components-tab-pane" role="tabpanel" aria-labelledby="components-tab" tabindex="0">
                        <div class="search">
                            <input class="form-control component-search" placeholder="Search components" type="text" data-vvveb-action="componentSearch" data-vvveb-on="keyup">
                            <button class="clear-backspace" data-vvveb-action="clearComponentSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="components-list" data-type="components">
                            <!-- Components will be populated here by VvvebJs -->
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="sections-tab-pane" role="tabpanel" aria-labelledby="sections-tab" tabindex="0">
                        <div class="search">
                            <input class="form-control section-search" placeholder="Search sections" type="text" data-vvveb-action="sectionSearch" data-vvveb-on="keyup">
                            <button class="clear-backspace" data-vvveb-action="clearSectionSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="sections-list" data-type="sections">
                            <!-- Sections will be populated here by VvvebJs -->
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="blocks-tab-pane" role="tabpanel" aria-labelledby="blocks-tab" tabindex="0">
                        <div class="search">
                            <input class="form-control block-search" placeholder="Search blocks" type="text" data-vvveb-action="blockSearch" data-vvveb-on="keyup">
                            <button class="clear-backspace" data-vvveb-action="clearBlockSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="blocks-list" data-type="blocks">
                            <!-- Blocks will be populated here by VvvebJs -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="right-panel">
            <div id="properties">
                <div class="header">
                    <a href="#" class="text-secondary">Properties</a>
                </div>
                
                <!-- Component Properties Tabs -->
                <ul class="nav nav-tabs nav-fill" id="properties-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-tab-pane" type="button" role="tab" aria-controls="content-tab-pane" aria-selected="true">Content</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="style-tab" data-bs-toggle="tab" data-bs-target="#style-tab-pane" type="button" role="tab" aria-controls="style-tab-pane" aria-selected="false">Style</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced-tab-pane" type="button" role="tab" aria-controls="advanced-tab-pane" aria-selected="false">Advanced</button>
                    </li>
                </ul>
                
                <!-- Tab content for component properties -->
                <div class="tab-content properties-tabs-content">
                    <div class="tab-pane fade show active" id="content-tab-pane" data-section="content" role="tabpanel" aria-labelledby="content-tab" tabindex="0">
                        <div class="section" data-section="default">
                            <div class="mt-3 mb-3" data-header="default">
                                <label class="header"><span>Properties</span></label>
                                <input class="header_check" type="checkbox" checked="true">
                            </div>
                            <div class="property-content"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="style-tab-pane" data-section="style" role="tabpanel" aria-labelledby="style-tab" tabindex="0">
                        <div class="section" data-section="default">
                            <div class="mt-3 mb-3" data-header="default">
                                <label class="header"><span>Styles</span></label>
                                <input class="header_check" type="checkbox" checked="true">
                            </div>
                            <div class="property-content"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="advanced-tab-pane" data-section="advanced" role="tabpanel" aria-labelledby="advanced-tab" tabindex="0">
                        <div class="section" data-section="default">
                            <div class="mt-3 mb-3" data-header="default">
                                <label class="header"><span>Advanced</span></label>
                                <input class="header_check" type="checkbox" checked="true">
                            </div>
                            <div class="property-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Add configuration panel -->
            <div id="configuration" style="display:none;">
                <div class="header">
                    <a href="#" class="text-secondary">Configuration</a>
                </div>
                <div class="component-properties">
                    <div class="mt-3 mb-3">
                        <label class="header"><span>Page Settings</span></label>
                    </div>
                    <div class="property-content">
                        <!-- Configuration options will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
        
        <div id="canvas">
            <div id="iframe-wrapper">
                <div id="iframe-layer">
                    <div id="highlight-box">
                        <div id="highlight-name"></div>
                        
                        <div id="section-actions">
                            <a id="add-section-btn" href="" title="Add section"><i class="fas fa-plus"></i></a>
                            <a id="add-component-btn" href="" title="Add component"><i class="fas fa-plus"></i></a>
                        </div>
                        
                        <div class="handle left-top" data-direction="left-top"></div>
                        <div class="handle left-center" data-direction="left-center"></div>
                        <div class="handle left-bottom" data-direction="left-bottom"></div>
                        <div class="handle center-top" data-direction="center-top"></div>
                        <div class="handle center-bottom" data-direction="center-bottom"></div>
                        <div class="handle right-top" data-direction="right-top"></div>
                        <div class="handle right-center" data-direction="right-center"></div>
                        <div class="handle right-bottom" data-direction="right-bottom"></div>
                        
                        <!-- Add the drag-btn element that's missing -->
                        <div id="drag-btn" class="drag-btn" title="Drag element"><i class="fas fa-arrows-alt"></i></div>
                    </div>
                    
                    <div id="select-box" class="select-box">
                        <div id="select-actions" class="select-actions">
                            <a id="drag-btn" href="#" title="Drag element"><i class="fas fa-arrows-alt"></i></a>
                            <a id="parent-btn" href="#" title="Select parent"><i class="fas fa-level-up-alt"></i></a>
                            <a id="up-btn" href="#" title="Move element up"><i class="fas fa-arrow-up"></i></a>
                            <a id="down-btn" href="#" title="Move element down"><i class="fas fa-arrow-down"></i></a>
                            <a id="clone-btn" href="#" title="Clone element"><i class="fas fa-copy"></i></a>
                            <a id="delete-btn" href="#" title="Remove element"><i class="fas fa-trash"></i></a>
                            <a id="edit-code-btn" href="#" title="Edit code"><i class="fas fa-code"></i></a>
                        </div>
                        
                        <div class="resize">
                            <div class="top-left"></div>
                            <div class="top-center"></div>
                            <div class="top-right"></div>
                            <div class="middle-left"></div>
                            <div class="middle-right"></div>
                            <div class="bottom-left"></div>
                            <div class="bottom-center"></div>
                            <div class="bottom-right"></div>
                        </div>
                    </div>
                </div>
                
                <iframe src="{{ route('pagebuilder.preview', $page->slug) }}" id="iframe1"></iframe>
            </div>
        </div>
    </div>
    
    <!-- Required elements for vvvebjs -->
    <div id="drop-highlight"></div>
    <div id="section-actions">
        <div class="internal">
            <button class="btn btn-dark btn-sm btn-up" title="Move Up"><i class="fas fa-arrow-up"></i></button>
            <button class="btn btn-dark btn-sm btn-down" title="Move Down"><i class="fas fa-arrow-down"></i></button>
            <button class="btn btn-dark btn-sm btn-clone" title="Clone Section"><i class="fas fa-copy"></i></button>
            <button class="btn btn-danger btn-sm btn-remove" title="Remove Section"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    
    <!-- Save reusable element button -->
    <div id="save-reusable-section" style="display:none;">
        <a id="save-reusable-btn" href="#" title="Save as reusable section"><i class="fas fa-save"></i></a>
    </div>
    
    <!-- Our form to save content -->
    <form id="vvvebjs-form" action="{{ route('pagebuilder.save-builder', $page->id) }}" method="POST" style="display:none">
        @csrf
        <input type="hidden" name="html" id="vvvebjs-html">
        <input type="hidden" name="css" id="vvvebjs-css">
        <input type="hidden" name="js" id="vvvebjs-js">
    </form>
    
    <!-- Modal code editor -->
    <div class="modal fade" id="codeEditorModal" tabindex="-1" role="dialog" aria-labelledby="codeEditorModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit HTML</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="codeEditor"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Third-party libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Define required custom elements before VvvebJs loads -->
    <script>
        // Define Vvveb configuration variables
        var bgcolorClasses = ["bg-primary", "bg-secondary", "bg-success", "bg-danger", "bg-warning", "bg-info", "bg-light", "bg-dark", "bg-white", "bg-transparent"];
        var colorClasses = ["text-primary", "text-secondary", "text-success", "text-danger", "text-warning", "text-info", "text-light", "text-dark", "text-white"];
        var buttonColorClasses = ["btn-primary", "btn-secondary", "btn-success", "btn-danger", "btn-warning", "btn-info", "btn-light", "btn-dark", "btn-white", "btn-link"];
        var buttonOutlineClasses = ["btn-outline-primary", "btn-outline-secondary", "btn-outline-success", "btn-outline-danger", "btn-outline-warning", "btn-outline-info", "btn-outline-light", "btn-outline-dark", "btn-outline-white"];
        var buttonSizeClasses = ["btn-sm", "btn-lg"];
        var alignmentClasses = ["left", "center", "right"];
        var borderClasses = ["border", "border-top", "border-end", "border-bottom", "border-start", "border-0", "border-top-0", "border-end-0", "border-bottom-0", "border-start-0"];
        var borderColorClasses = ["border-primary", "border-secondary", "border-success", "border-danger", "border-warning", "border-info", "border-light", "border-dark", "border-white"];
        var textPrimaryColorClasses = ["primary", "secondary", "success", "danger", "warning", "info", "light", "dark", "white"];
        var positionClasses = ["position-static", "position-relative", "position-absolute", "position-fixed", "position-sticky"];
        
        // Initialize Vvveb object to add configurations
        var Vvveb = Vvveb || {};
        
        // Define VideoInput and AudioInput but NOT FileUploadInput (which is defined by VvvebJs)
        window.VideoInput = class VideoInput extends HTMLElement {
            constructor() { super(); }
            init(node) { return this; }
            onChange(node, value) { return value; }
        };
        
        window.AudioInput = class AudioInput extends HTMLElement {
            constructor() { super(); }
            init(node) { return this; }
            onChange(node, value) { return value; }
        };
        
        // Set up panel selectors and configuration
        Vvveb.ComponentsGroup = {};
        Vvveb.SectionsGroup = {};
        Vvveb.BlocksGroup = {};
        
        // Important selector configurations
        Vvveb.componentsPanel = "#components-tab-pane .components-list";
        Vvveb.sectionsPanel = "#sections-tab-pane .sections-list";
        Vvveb.blocksPanel = "#blocks-tab-pane .blocks-list";
        Vvveb.mainComponentsPanel = "#components-tab-pane";
        Vvveb.componentPropertiesElement = ".properties-tabs-content .tab-pane";
        Vvveb.componentPropertiesDefaultSection = "content";
    </script>
    
    <!-- Load builder libraries explicitly -->
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/builder.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/undo.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/inputs.js') }}"></script>
    
    <!-- Add crucial missing Video/Audio Input definitions before components-html.js loads -->
    <script>
        // Define VideoInput - this MUST be defined before components-html.js loads
        if (typeof window.VideoInput === 'undefined') {
            console.log("Defining VideoInput before components-html.js loads");
            window.VideoInput = class VideoInput extends HTMLElement {
                constructor() { super(); }
                init(node) { return this; }
                onChange(node, value) { return value; }
            };
            // Register with customElements if not already registered
            try {
                customElements.define('video-input', window.VideoInput);
            } catch (e) {
                console.log("Note: VideoInput already registered with customElements");
            }
        }
        
        // Define AudioInput - this MUST be defined before components-html.js loads
        if (typeof window.AudioInput === 'undefined') {
            console.log("Defining AudioInput before components-html.js loads");
            window.AudioInput = class AudioInput extends HTMLElement {
                constructor() { super(); }
                init(node) { return this; }
                onChange(node, value) { return value; }
            };
            // Register with customElements if not already registered
            try {
                customElements.define('audio-input', window.AudioInput);
            } catch (e) {
                console.log("Note: AudioInput already registered with customElements");
            }
        }
    </script>
    
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/components-common.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/components-html.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/components-elements.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/section.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/components-bootstrap5.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/components-widgets.js') }}"></script>
    
    <!-- Additional plugins -->
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('modules/pagebuilder/vvvebjs/libs/builder/plugin-codemirror.js') }}"></script>
    
    <script>
        // Modal Code Editor
        Vvveb.ModalCodeEditor = {
            modal: null,
            editor: null,
            
            init: function(modal, editor) {
                this.modal = modal;
                this.editor = editor;
                
                if (this.editor) {
                    this.textarea = this.editor.get(0);
                    this.codeEditor = CodeMirror.fromTextArea(this.textarea, {
                        mode: "text/html",
                        lineNumbers: true,
                        autofocus: true,
                        lineWrapping: true,
                        theme: "material"
                    });
                }
                
                this.open = function(content) {
                    if (this.codeEditor) {
                        this.codeEditor.setValue(content || "");
                        this.codeEditor.refresh();
                    }
                    this.modal.modal('show');
                };
                
                this.close = function() {
                    this.modal.modal('hide');
                };
                
                this.save = function() {
                    if (this.codeEditor) {
                        return this.codeEditor.getValue();
                    }
                    return '';
                };
                
                var self = this;
                
                this.modal.find('.btn-save').on('click', function(e) {
                    e.preventDefault();
                    var content = self.save();
                    if (Vvveb.Builder.selectedEl) {
                        Vvveb.Builder.selectedEl.html(content);
                    }
                    self.close();
                });
                
                this.modal.find('.btn-cancel').on('click', function(e) {
                    e.preventDefault();
                    self.close();
                });
            }
        };
        
        // Configure Vvveb before initialization
        Vvveb.ComponentsGroup = {};
        Vvveb.SectionsGroup = {};
        Vvveb.BlocksGroup = {};
        
        // Important selector configurations
        Vvveb.componentsPanel = "#components-tab-pane .components-list";
        Vvveb.sectionsPanel = "#sections-tab-pane .sections-list";
        Vvveb.blocksPanel = "#blocks-tab-pane .blocks-list";
        Vvveb.mainComponentsPanel = "#components-tab-pane";
        Vvveb.componentPropertiesElement = ".properties-tabs-content .tab-pane";
        Vvveb.componentPropertiesDefaultSection = "content";
        
        // Custom check to fix errors with undefined components
        Vvveb.safeComponent = function(component) {
            if (!component) return null;
            
            // Try to find the component in Vvveb.Components
            if (Vvveb.Components && Vvveb.Components.get && typeof Vvveb.Components.get === 'function') {
                return Vvveb.Components.get(component);
            }
            
            return null;
        };
        
        // Fix for missing VideoInput custom element
        // We need this because VvvebJs tries to use VideoInput but it's not defined
        window.fixCustomElements = function() {
            console.log("Running custom element fix...");
            
            // Only define VideoInput if it doesn't already exist
            if (typeof window.VideoInput === 'undefined') {
                console.log("VideoInput not found, defining...");
                window.VideoInput = class VideoInput extends HTMLElement {
                    constructor() { super(); }
                    init(node) { return this; }
                    onChange(node, value) { return value; }
                };
                customElements.define('video-input', VideoInput);
            }
            
            // Only define AudioInput if it doesn't already exist
            if (typeof window.AudioInput === 'undefined') {
                console.log("AudioInput not found, defining...");
                window.AudioInput = class AudioInput extends HTMLElement {
                    constructor() { super(); }
                    init(node) { return this; }
                    onChange(node, value) { return value; }
                };
                customElements.define('audio-input', AudioInput);
            }
            
            // Only define SliderInput if it doesn't already exist
            if (typeof window.SliderInput === 'undefined') {
                console.log("SliderInput not found, defining...");
                window.SliderInput = class SliderInput extends HTMLElement {
                    constructor() { super(); }
                    init(node) { return this; }
                    onChange(node, value) { return value; }
                };
                customElements.define('slider-input', SliderInput);
            }
            
            console.log("Custom element fix completed");
        };
        
        // Document ready function
        $(document).ready(function() {
            // Run fixes for custom elements before initializing VvvebJs
            window.fixCustomElements();
            
            // Initialize the modal code editor
            Vvveb.ModalCodeEditor.init($('#codeEditorModal'), $('#codeEditor'));
            
            // Define helper functions
            function safeQuerySelector(element, selector) {
                if (!element) return null;
                
                try {
                    return element.querySelector(selector);
                } catch (e) {
                    console.error("Error in querySelector", e);
                    return null;
                }
            }
            
            // Store the preview URL to be used by the iframe
            const previewUrl = "{{ route('pagebuilder.preview', $page->slug) }}";
            
            // Set the iframe src to ensure it loads properly
            $("#iframe1").attr("src", previewUrl);
            
            // Ensure GUI exists 
            if (!Vvveb.Builder.Gui) {
                console.log("Creating Gui object");
                Vvveb.Builder.Gui = {
                    init: function() {
                        console.log("Custom GUI initialized");
                    }
                };
            }
            
            // Override the problematic render method in Vvveb.Components
            console.log("Setting up component render override...");
            if (Vvveb.Components && typeof Vvveb.Components.render === 'function') {
                Vvveb.Components._originalRender = Vvveb.Components.render;
                
                Vvveb.Components.render = function(component, html, data) {
                    try {
                        console.log("Custom render for component:", component);
                        
                        // Skip rendering if component is not provided
                        if (!component) {
                            console.warn("No component specified for rendering");
                            return "";
                        }
                        
                        // Handle the panel element
                        let panel = null;
                        if (typeof html === "string") {
                            panel = document.querySelector(html);
                        } else {
                            panel = html;
                        }
                        
                        if (!panel) {
                            console.error("Panel not found for component rendering:", html);
                            return "";
                        }
                        
                        // Create a simplified properties display
                        panel.innerHTML = '';
                        
                        // Get component info
                        const componentObj = Vvveb.Components.get(component);
                        if (!componentObj) {
                            panel.innerHTML = `
                                <div class="component-properties p-3">
                                    <h5>Component Properties</h5>
                                    <p>No component selected or component not found.</p>
                                </div>
                            `;
                            return "";
                        }
                        
                        // Create simple header
                        const header = document.createElement('div');
                        header.className = 'component-header p-2 mb-3 border-bottom';
                        header.innerHTML = `<h5>${componentObj.name || "Component"}</h5>`;
                        panel.appendChild(header);
                        
                        // Only attempt to render properties if componentObj has properties
                        if (componentObj.properties && Array.isArray(componentObj.properties)) {
                            // Create container for properties
                            const propertiesContainer = document.createElement('div');
                            propertiesContainer.className = 'component-properties p-2';
                            
                            // Safe rendering of properties
                            componentObj.properties.forEach(function(property) {
                                if (property && property.name) {
                                    const propertyDiv = document.createElement('div');
                                    propertyDiv.className = 'mb-3';
                                    propertyDiv.innerHTML = `
                                        <label class="form-label">${property.name}</label>
                                        <input type="text" class="form-control" data-property="${property.key || property.name}">
                                    `;
                                    propertiesContainer.appendChild(propertyDiv);
                                }
                            });
                            
                            panel.appendChild(propertiesContainer);
                        } else {
                            // No properties
                            const noProperties = document.createElement('div');
                            noProperties.className = 'p-3';
                            noProperties.innerHTML = '<p>No editable properties for this component.</p>';
                            panel.appendChild(noProperties);
                        }
                        
                        console.log("Custom render completed successfully");
                        return "";
                    } catch (error) {
                        console.error("Error in custom render:", error);
                        return "";
                    }
                };
            }
            
            // Implementing a custom Builder.init method to handle initialization without errors
            Vvveb.Builder.init = Vvveb.Builder.init || function(id, callback) {
                console.log("Custom Builder.init called for", id);
                
                // Get the iframe element
                var iframe = document.getElementById(id);
                if (!iframe) {
                    console.error("Iframe not found:", id);
                    return;
                }
                
                // Set basic properties
                this.iframe = iframe;
                this.id = id;
                
                // Set up event listener for iframe load
                iframe.addEventListener("load", function() {
                    console.log("Iframe loaded");
                    
                    try {
                        // Safely call the callback if provided
                        if (typeof callback === 'function') {
                            callback();
                        }
                    } catch (e) {
                        console.error("Error in iframe load callback:", e);
                    }
                });
                
                console.log("Builder initialization complete");
            };
            
            // Initialize the builder
            Vvveb.Builder.init('iframe1', function() {
                console.log("VvvebJs Builder initialized");
                
                // Load content if exists
                var htmlContent = @json($page->html_content);
                var cssContent = @json($page->css_content);
                
                if (htmlContent) {
                    Vvveb.Builder.setHtml(htmlContent);
                }
                
                // Safety check for Vvveb.Builder.Gui before initializing
                if (Vvveb.Builder.Gui && typeof Vvveb.Builder.Gui.init === 'function') {
                    console.log("Initializing VvvebJs GUI");
                    Vvveb.Builder.Gui.init();
                } else {
                    console.error("VvvebJs GUI not available");
                    
                    // Create minimal GUI if missing
                    if (!Vvveb.Builder.Gui) {
                        console.log("Creating minimal GUI object");
                        Vvveb.Builder.Gui = {
                            init: function() {
                                console.log("Minimal GUI initialized");
                            }
                        };
                    }
                }
            });
            
            // Save button handler
            $("#save-btn").on("click", function() {
                // Get the current HTML and CSS
                var html = Vvveb.Builder.getHtml();
                var css = Vvveb.Builder.getCss();
                
                // Set the values in the form
                $("#vvvebjs-html").val(html);
                $("#vvvebjs-css").val(css);
                $("#vvvebjs-js").val("");
                
                // Submit the form
                $("#vvvebjs-form").submit();
            });
        });
    </script>
    
    <!-- VvvebJs custom GUI implementation -->
    <script>
        // Create custom GUI implementation before initializing the builder
        $(document).ready(function() {
            console.log("Setting up custom VvvebJs GUI implementation...");
            
            // Make sure Vvveb.Builder exists
            if (typeof Vvveb !== 'undefined' && Vvveb.Builder) {
                // Create GUI namespace if it doesn't exist
                Vvveb.Builder.Gui = Vvveb.Builder.Gui || {};
                
                // Implement the required init method
                Vvveb.Builder.Gui.init = function() {
                    console.log("Custom GUI initialized");
                    
                    // Handle component selection
                    $(".components-list li ol li").on("click", function(event) {
                        var component = $(this).data("component");
                        if (component) {
                            Vvveb.Components.render(component);
                        }
                        event.preventDefault();
                        return false;
                    });
                    
                    // Add drag and drop handlers
                    $("#drag-btn").on("mousedown", function(event) {
                        event.preventDefault();
                        return false;
                    });
                    
                    // Initialize text editing toolbar
                    $("#text-editor-toolbar button").on("click", function(event) {
                        var command = $(this).data("command");
                        if (command) {
                            document.execCommand(command, false, null);
                        }
                        event.preventDefault();
                        return false;
                    });
                    
                    console.log("Custom GUI handlers attached");
                };
                
                // Override the problematic _initBox method with our own implementation
                Vvveb.Builder._originalInitBox = Vvveb.Builder._initBox;
                Vvveb.Builder._initBox = function() {
                    console.log("Using safer _initBox implementation");
                    
                    // Helper function to safely add event listeners
                    const safeAddEventListener = function(id, event, callback) {
                        const element = document.getElementById(id);
                        if (element) {
                            element.addEventListener(event, callback);
                            console.log(`Added ${event} listener to #${id}`);
                        } else {
                            console.warn(`Couldn't find element #${id} to add ${event} listener`);
                        }
                    };
                    
                    // Add event listeners only for elements that exist
                    safeAddEventListener("drag-btn", "mousedown", function(event) {
                        if (event.which == 1) {//left click
                            console.log("Drag started");
                            event.preventDefault();
                            return false;
                        }
                    });
                    
                    safeAddEventListener("up-btn", "click", function(event) {
                        console.log("Move up clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("down-btn", "click", function(event) {
                        console.log("Move down clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("clone-btn", "click", function(event) {
                        console.log("Clone clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("parent-btn", "click", function(event) {
                        console.log("Parent clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("delete-btn", "click", function(event) {
                        console.log("Delete clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("edit-code-btn", "click", function(event) {
                        console.log("Edit code clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("save-reusable-btn", "click", function(event) {
                        console.log("Save reusable clicked");
                        event.preventDefault();
                        return false;
                    });
                    
                    safeAddEventListener("close-section-btn", "click", function(event) {
                        console.log("Close section clicked");
                        const addSectionBox = document.getElementById("add-section-box");
                        if (addSectionBox) {
                            addSectionBox.style.display = "none";
                        }
                        event.preventDefault();
                        return false;
                    });
                    
                    console.log("Safe _initBox completed");
                };
                
                // Override the _frameLoaded method to fix the classList error
                Vvveb.Builder._originalFrameLoaded = Vvveb.Builder._frameLoaded;
                Vvveb.Builder._frameLoaded = function(frameDoc) {
                    console.log("Safe _frameLoaded called");
                    
                    try {
                        // Safely remove the loading message
                        const loadingMessage = document.querySelector(".loading-message");
                        if (loadingMessage) {
                            loadingMessage.classList.remove("active");
                        }
                        
                        // Call the rest of the original method if it exists
                        if (this._originalFrameLoaded) {
                            try {
                                this._originalFrameLoaded(frameDoc);
                            } catch (e) {
                                console.error("Error in original _frameLoaded:", e);
                            }
                        }
                    } catch (e) {
                        console.error("Error in safe _frameLoaded:", e);
                    }
                };
            }
        });
    </script>
    
    <script>
        // Create a flag to indicate our patches are installed
        window.vvvebPatched = false;
        
        // Function to apply patches to VvvebJs
        window.applyVvvebPatches = function() {
            if (window.vvvebPatched) return; // Only apply patches once
            
            console.log("Applying VvvebJs patches...");
            
            // Wait for Vvveb to be defined
            if (typeof Vvveb === 'undefined' || !Vvveb.Builder) {
                console.warn("Vvveb not defined yet, will retry in 100ms");
                setTimeout(window.applyVvvebPatches, 100);
                return;
            }
            
            // Store original functions
            Vvveb.Builder._originalInit = Vvveb.Builder.init;
            
            // Replace init with our own version
            Vvveb.Builder.init = function(id, callback) {
                console.log("Custom Builder.init running for", id);
                
                // The iframe element
                const iframe = document.getElementById(id);
                if (!iframe) {
                    console.error("Iframe not found:", id);
                    return;
                }
                
                // Set basic properties
                this.iframe = iframe;
                this.id = id;
                
                // Use a completely custom initialization flow
                try {
                    // Set up event listener for iframe load
                    iframe.addEventListener("load", function() {
                        console.log("Iframe loaded successfully");
                        
                        // Save reference to content window and document
                        Vvveb.Builder.frameWindow = iframe.contentWindow;
                        Vvveb.Builder.frameDoc = iframe.contentWindow.document;
                        Vvveb.Builder.frameHtml = Vvveb.Builder.frameDoc.documentElement;
                        Vvveb.Builder.frameHead = Vvveb.Builder.frameDoc.head;
                        Vvveb.Builder.frameBody = Vvveb.Builder.frameDoc.body;
                        
                        // Custom initialization without calling _initBox
                        
                        // Make sure loading message is hidden
                        const loadingMessage = document.querySelector(".loading-message");
                        if (loadingMessage) {
                            loadingMessage.classList.remove("active");
                        }
                        
                        // Call user callback if provided
                        if (typeof callback === 'function') {
                            setTimeout(function() {
                                try {
                                    callback();
                                } catch (e) {
                                    console.error("Error in iframe callback", e);
                                }
                            }, 100);
                        }
                    });
                    
                    console.log("Custom Builder initialization complete");
                } catch (e) {
                    console.error("Error in custom Builder.init", e);
                }
            };
            
            // Mark patches as installed
            window.vvvebPatched = true;
            console.log("VvvebJs patches applied successfully");
        };
        
        // Apply patches when the document is ready
        document.addEventListener("DOMContentLoaded", function() {
            window.applyVvvebPatches();
        });
    </script>
</body>
</html>
