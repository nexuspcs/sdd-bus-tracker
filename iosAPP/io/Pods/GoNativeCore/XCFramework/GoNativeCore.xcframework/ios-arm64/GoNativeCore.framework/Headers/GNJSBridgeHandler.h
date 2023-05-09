//
//  GNJSBridgeHandler.h
//  GoNativeCore
//
//  Created by bld on 11.11.22.
//

#import <Foundation/Foundation.h>
#import <WebKit/WebKit.h>

NS_ASSUME_NONNULL_BEGIN

@interface WebViewControllerProp : NSObject
- (void)downloadImage:(NSURL*)url;
- (void)handleUrl:(NSURL *)url query:(NSDictionary*)query;
- (void)shareUrl:(NSURL*)url fromView:(UIView*)view;
@end

@interface WebViewController : UIViewController
- (NSDictionary *)getConnectivity;
- (void)handleJSBridgeFunctions:(id)data;
- (UIViewController *)getTopPresentedViewController;
- (void)openWindowWithUrl:(NSString *)urlString;
- (void)refreshPage;
- (void)requestLocation;
- (void)runCustomCode:(NSDictionary *)query;
- (void)runGonativeDeviceInfoWithCallback:(NSString*)callback;
- (void)runJavascriptWithCallback:(NSString *)callback data:(NSDictionary*)data;
- (void)setCssTheme:(NSString *)mode andPersistData:(BOOL)persist;
- (void)setNativeTheme:(NSString *)mode;
- (void)sharePageWithUrl:(NSString*)url text:(NSString*)text sender:(id _Nullable)sender;
- (void)showNavigationItemButtonsAnimated:(BOOL)animated;
- (void)startWatchingLocation;
- (void)stopWatchingLocation;
- (void)themeManagerHandleUrl:(NSURL *)url query:(NSDictionary *)query;
- (void)updateWindowsController;

@property WebViewControllerProp *backgroundAudio;
@property WebViewControllerProp *configPreferences;
@property WebViewControllerProp *documentSharer;
@property WebViewControllerProp *logManager;
@property WebViewControllerProp *regexRulesManager;
@property WebViewControllerProp *registrationManager;
@property WebViewControllerProp *tabManager;
@property WebViewControllerProp *toolbarManager;

@property (nullable) NSString *connectivityCallback;
@property NSURLRequest *currentRequest;
@property (nullable) UIView *defaultTitleView;
@property BOOL javascriptTabs;
@property BOOL restoreBrightnessOnNavigation;
@property CGFloat savedScreenBrightness;
@property BOOL sidebarItemsEnabled;
@property WKWebView *wkWebview;
@end

@interface GNJSBridgeHandler : NSObject
+(GNJSBridgeHandler *)shared;

- (void)handleUrl:(NSURL *)url query:(NSDictionary *)query wvc:(WebViewController *)wvc;
@end

NS_ASSUME_NONNULL_END
