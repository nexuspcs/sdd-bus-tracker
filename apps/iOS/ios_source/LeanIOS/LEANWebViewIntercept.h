//
//  LEANWebViewIntercept.h
//  GoNativeIOS
//
//  Created by Weiyin He on 4/12/14.
// Copyright (c) 2014 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface LEANWebViewIntercept : NSURLProtocol
+(void)register;
@end

@interface LEANWebviewInterceptTracker : NSObject
@property NSURLRequest *currentRequest;
+ (LEANWebviewInterceptTracker*)sharedTracker;
@end
