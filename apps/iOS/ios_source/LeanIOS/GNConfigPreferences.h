//
//  GNConfigPreferences.h
//  GonativeIO
//
//  Created by Weiyin He on 3/16/18.
//  Copyright © 2018 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface GNConfigPreferences : NSObject
+ (instancetype)sharedPreferences;

- (void)handleUrl:(NSURL *)url query:(NSDictionary*)query;
- (void)setInitialUrl:(NSString*)url;
- (NSString*)getInitialUrl;
@end
