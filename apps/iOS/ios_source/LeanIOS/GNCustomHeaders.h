//
//  GNCustomHeaders.h
//  GoNativeIOS
//
//  Created by Weiyin He on 5/1/17.
//  Copyright © 2017 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <WebKit/WebKit.h>

@interface GNCustomHeaders : NSObject
+(NSDictionary*)getCustomHeaders;
-(NSURLRequest*)modifyRequest:(NSURLRequest*)request;
-(BOOL)shouldModifyRequest:(NSURLRequest *)request webview:(WKWebView *)webview;
@end
