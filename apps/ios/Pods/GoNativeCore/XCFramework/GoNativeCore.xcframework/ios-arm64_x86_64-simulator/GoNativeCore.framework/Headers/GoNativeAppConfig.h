//
//  GoNativeAppConfig.h
//  GoNativeIOSLibrary
//
//  Created by Weiyin He on 1/7/16.
//  Copyright © 2016 Gonative.io LLC. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

static NSString *kGoNativeAppConfigNotificationUserAgentReady = @"io.gonative.ios.LEANAppConfig.useragentready";
static NSString *kLEANAppConfigNotificationProcessedMenu = @"io.gonative.ios.LEANAppConfig.processedMenu";
static NSString *kLEANAppConfigNotificationProcessedTabNavigation = @"io.gonative.ios.LEANAppConfig.processedTabNavigation";
static NSString *kLEANAppConfigNotificationProcessedWebViewPools = @"io.gonative.ios.LEANAppConfig.processedWebViewPools";
static NSString *kLEANAppConfigNotificationProcessedSegmented = @"io.gonative.ios.LEANAppConfig.processedSegmented";
static NSString *kLEANAppConfigNotificationProcessedNavigationTitles = @"io.gonative.ios.LEANAppConfig.processedNavigationTitles";
static NSString *kLEANAppConfigNotificationProcessedNavigationLevels = @"io.gonative.ios.LEANAppConfig.processedNavigationLevels";
static NSString *kLEANAppConfigNotificationAppTrackingStatusChanged = @"io.gonative.ios.LEANAppConfig.appTrackingStatusChanged";

typedef enum : NSUInteger {
     LEANToolbarVisibilityAlways,
     LEANToolbarVisibilityAnyItemEnabled
 } LEANToolbarVisibility;

typedef enum : NSUInteger {
    LEANToolbarVisibilityByPagesAll,
    LEANToolbarVisibilityByPagesSpecific
} LEANToolbarVisibilityByPages;

typedef enum : NSUInteger {
    LEANToolbarVisibilityByBackButtonActive,
    LEANToolbarVisibilityByBackButtonAlways
} LEANToolbarVisibilityByBackButton;

typedef enum : NSUInteger {
    GoNativeScreenOrientationUnspecified,
    GoNativeScreenOrientationPortrait,
    GoNativeScreenOrientationLandscape
} GoNativeScreenOrientation;

@interface RegexEnabled : NSObject
@property NSPredicate *regex;
@property BOOL enabled;
@end

@interface ActionSelection : NSObject
@property NSPredicate *regex;
@property NSString *identifier;
@end

@interface GoNativeAppConfig : NSObject

// general
@property NSError *configError;
@property (readonly) NSURL *initialURL;
@property NSString *initialHost;
@property NSString *appName;
@property NSString *publicKey;
@property NSString *userAgentAdd;
@property NSString *forceUserAgent;
@property NSString *userAgent;
@property BOOL userAgentReady;
@property NSMutableArray *userAgentRegexes;
@property NSMutableArray *userAgentStrings;
@property BOOL useWKWebView;
@property NSUInteger forceSessionCookieExpiry;
@property NSArray *replaceStrings;
@property BOOL disableConfigUpdater;
@property BOOL disableEventRecorder;
@property BOOL enableWindowOpen;
@property GoNativeScreenOrientation forceScreenOrientation;
@property BOOL keepScreenOn;
@property BOOL iosFullScreenWebview;
@property NSDictionary *customHeaders;
@property NSArray<NSPredicate*> *nativeBridgeUrls;
@property NSMutableDictionary *listeners;

// development tools
@property BOOL enableWebConsoleLogs;

// permissions
@property BOOL iOSRequestATTConsentOnLoad;

// navigation
@property NSMutableDictionary *menus;
@property NSURL *loginDetectionURL;
@property NSMutableArray *loginDetectRegexes;
@property NSMutableArray *loginDetectLocations;
@property BOOL showNavigationMenu;
@property NSMutableArray<NSPredicate*> *sidebarEnabledRegexes;
@property NSMutableArray<NSNumber*> *sidebarIsEnabled;
@property NSMutableArray *navStructureLevels;
@property NSMutableArray *navTitles;
@property NSMutableArray *regexInternalExternal;
@property NSMutableArray *regexIsInternal;
@property (readonly) NSDictionary *redirects;
@property NSString *profilePickerJS;
@property NSString *userIdRegex;
@property BOOL useWebpageTitle;
@property NSArray *segmentedControlItems;
@property NSString *customUrlScheme;
@property BOOL disableDocumentOpenWith;
@property BOOL windowOpenHideNavbar;

@property NSMutableDictionary *tabMenus;
@property NSMutableArray *tabMenuRegexes;
@property NSMutableArray *tabMenuIDs;

@property NSMutableDictionary *actions;
@property NSMutableArray<ActionSelection *> *actionSelection;

@property BOOL toolbarEnabled;
@property LEANToolbarVisibility toolbarVisibility;
@property LEANToolbarVisibilityByPages toolbarVisibilityByPages;
@property LEANToolbarVisibilityByBackButton toolbarVisibilityByBackButton;
@property NSArray *toolbarItems;
@property NSMutableArray<RegexEnabled*> *toolbarRegexes;

@property BOOL pinchToZoom;
@property BOOL pullToRefresh;
@property BOOL swipeGestures;
@property BOOL swipeGesturesEdge;
@property NSInteger maxWindows;
@property BOOL maxWindowsAutoClose;

@property BOOL iosShowOfflinePage;
@property NSNumber *iosConnectionOfflineTime;

// script
@property NSString *customJs;
@property NSString *iosCustomJs;

// styling
@property NSString *iosTheme;
@property NSString *iosDarkMode;
@property NSString *customCss;
@property NSString *iosCustomCss;
@property NSNumber *forceViewportWidth;
@property NSString *stringViewport;
@property UIColor *tintColor;
@property UIColor *tintColorDark;
@property UIColor *titleTextColor;
@property UIColor *titleTextColorDark;
@property UIColor *iosStatusBarBackgroundColor;
@property UIColor *iosStatusBarBackgroundColorDark;
@property BOOL iosEnableBlurInStatusBar;
@property BOOL iosEnableOverlayInStatusBar;
@property NSString *iosStatusBarStyle;
@property UIColor *iosNavigationBarTintColor;
@property UIColor *iosNavigationBarTintColorDark;
@property UIColor *iosTabBarTintColor;
@property UIColor *iosTabBarTintColorDark;
@property BOOL showToolbar;
@property BOOL showNavigationBar;
@property BOOL showNavigationBarWithNavigationLevels;
@property NSMutableArray *navigationTitleImageRegexes;
@property NSNumber *menuAnimationDuration;
@property NSNumber *interactiveDelay;
@property UIFont *iosSidebarFont;
@property UIColor *iosSidebarTextColor;
@property UIColor *iosSidebarTextColorDark;
@property UIColor *iosSidebarBackgroundColor;
@property UIColor *iosSidebarBackgroundColorDark;
@property NSNumber *hideWebviewAlpha;
@property BOOL disableAnimations;
@property BOOL iosAutoHideHomeIndicator;


// forms
@property NSDictionary *loginConfig;
@property NSURL *loginURL;
@property BOOL loginIsFirstPage;
@property BOOL loginLaunchBackground;
@property BOOL loginIconImage;
@property NSURL *signupURL;
@property NSDictionary *signupConfig;
@property NSArray *interceptForms;


// services
@property BOOL analytics;
@property NSInteger idsite_test;
@property NSInteger idsite_prod;
@property BOOL backgroundLocationEnabled;
@property BOOL usesForegroundLocation;

// onesignal integration
@property BOOL oneSignalEnabled;
@property NSString *oneSignalAppId;
@property BOOL oneSignalAutoRegister;
@property NSURL *oneSignalTagsJsonUrl;
@property BOOL oneSignalRequiresUserPrivacyConsent;
@property BOOL oneSignalIosSoftPrompt;
@property BOOL oneSignalForegroundNotificationsEnabled;

// Xtremepush integration
@property BOOL xtremepushEnabled;
@property NSString *xtremepushAppKey;
@property BOOL xtremepushAutoRegister;

// CleverPush
@property BOOL cleverPushEnabled;
@property NSString *cleverPushChannelId;
@property BOOL cleverPushAutoRegister;

// IBM Push
@property BOOL ibmpushEnabled;
@property NSDictionary *ibmpushConfig;

// Facebook SDK
@property BOOL facebookEnabled;
@property NSString *facebookAppId;
@property NSString *facebookDisplayName;
@property BOOL facebookAutoLogging;
@property BOOL facebookLogin;
@property NSString* facebookClientToken;

// Google Sign-In
@property BOOL googleSignInEnabled;
@property NSString *googleClientID;
@property NSString *googleUrlScheme;

// Apple Sign-In
@property BOOL appleSignInEnabled;
@property NSString *appleClientID;

// Adjust SDK
@property BOOL adjustEnabled;
@property NSString *adjustAppToken;
@property NSString *adjustEnvironment;

// identity service
@property NSArray *checkIdentityUrlRegexes;
@property NSURL *identityEndpointUrl;

// registration service
@property NSArray *registrationEndpoints;

// touch id
@property NSArray *authAllowedUrls;

// in-app purchase
@property BOOL iapEnabled;
@property NSURL *iapProductsUrl;
@property NSURL *iapPostUrl;

// admob ads
@property BOOL admobEnabled;
@property NSString* admobApplicationId;
@property NSString* admobBannerAdUnitId;
@property NSString* admobInterstitialAdUnitId;
@property BOOL admobShowBanner;

// Card.IO scanning
@property BOOL cardIOEnabled;

// Scandit barcode scanning
@property BOOL scanditEnabled;
@property NSString *scanditLicenseKey;

// Share extension
@property BOOL shareEnabled;
@property NSString *iosAppGroup;

// NFC
@property BOOL nfcEnabled;

// Couples
@property BOOL couplesEnabled;
@property NSString *couplesLicenseKey;

// Episerver
@property BOOL episerverEnabled;
@property NSString *episerverAuthToken;

// Root / Jailbreak detection
@property BOOL rootDetectEnabled;

// Twilio
@property BOOL twilioEnabled;

// iBeacons
@property BOOL beaconEnabled;

// SalesForce MarketingCloud MobilePush
@property BOOL salesforceMobilepushEnabled;
@property BOOL salesforceRequiresUserPrivacyConsent;
@property NSString* salesforceAppID;
@property NSString* salesforceAccessToken;
@property NSString* salesforceAppEndpoint;
@property NSString* salesforceMID;
@property BOOL salesforceDelayRegistrationUntilContactKeyIsSet;

// Kaltura
@property BOOL kalturaEnabled;
@property NSNumber* kalturaPartnerId;
@property NSString* kalturaCastReceiverAppId;
@property NSString* kalturaServerUrl;

// Cordial
@property BOOL cordialEnabled;
@property NSString* cordialAccountKey;
@property NSString* cordialChannelKey;
@property BOOL cordialPushNotificationsEnabled;

// Appsflyer
@property BOOL appsflyerEnabled;
@property NSString* appsflyerDevKey;
@property NSString* appsflyerAppleId;

// local settings
@property BOOL localSettingsEnabled;

// firebase analytics
@property BOOL firebaseAnalyticsEnabled;

// barcode scanner
@property BOOL barcodeScannerEnabled;
@property NSString *barcodeScannerPrompt;

// App reviews
@property BOOL appReviewEnabled;
@property NSString* appReviewAppStoreId;

// Plaid
@property BOOL plaidEnabled;

// Braze
@property BOOL brazeEnabled;
@property NSString* brazeApiKey;
@property NSString* brazeEndpointKey;
@property BOOL brazeAutoRegister;

// MoEngage
@property BOOL moengageEnabled;
@property NSString* moengageAppId;
@property NSNumber* moengageDataCenter;
@property BOOL moengageAutoRegister;

// Intercom
@property BOOL intercomEnabled;
@property NSString* intercomApiKey;
@property NSString* intercomAppId;

// Moxo
@property BOOL moxoEnabled;
@property NSString* moxoDomain;

// Enterprise
@property BOOL copyPasteBlockEnabled;
@property BOOL appMaskingEnabled;

// misc
@property NSPredicate *forceLandscapeMatch;
@property BOOL showShareButton;
@property BOOL enableChromecast;
@property BOOL allowZoom;
@property NSString *postLoadJavascript;

// simulator
@property BOOL isSimulator;
@property BOOL isSimulating;
@property UIImage *appIcon;
@property UIImage *sidebarIcon;
@property UIImage *navigationTitleIcon;

@property NSArray *webviewPools;


+ (GoNativeAppConfig *)sharedAppConfig;
+ (NSURL*)urlForOtaConfig;
+ (NSURL*)urlForSimulatorConfig;
+ (NSURL*)urlForSimulatorIcon;
+ (NSURL*)urlForSimulatorSidebarIcon;
+ (NSURL*)urlForSimulatorNavTitleIcon;
- (void)setupFromJsonFiles;
- (NSMutableDictionary*)getSidebarNavigation;

- (void)setSidebarNavigation:(NSArray*)items;
- (void)setSidebarNavigation:(NSArray*)items persist:(BOOL)persist;
- (void)setNavigationTitles:(NSDictionary*)navigationTitles persist:(BOOL)persist;
- (void)setNavigationLevels:(NSDictionary*)navigationLevels persist:(BOOL)persist;
- (void)setMaxWindows:(NSInteger)maxWindows persist:(BOOL)persist autoClose:(BOOL)autoClose;
- (void)initializeRegexRules:(NSArray **)regexArray isInternalArray:(NSArray **)isInternalArray;
- (void)setRegexRules:(NSArray *)rules regexArray:(NSArray **)regexArray isInternalArray:(NSArray **)isInternalArray;

- (BOOL)shouldShowNavigationTitleImageForUrl:(NSString*)url;
-(NSString*)userAgentForUrl:(NSURL*)url;
- (BOOL)shouldShowSidebarForUrl:(NSString*)url;

- (BOOL)shouldProcessJsBridge;

@end
