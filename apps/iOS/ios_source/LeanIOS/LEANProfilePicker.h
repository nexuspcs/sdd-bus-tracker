//
//  LEANProfilePicker.h
//  GoNativeIOS
//
//  Created by Weiyin He on 5/14/14.
// Copyright (c) 2014 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface LEANProfilePicker : NSObject

@property NSMutableArray *names;
@property NSMutableArray *links;
@property NSInteger selectedIndex;

- (void)parseJson:(NSString*)json;
- (BOOL)hasProfiles;

@end
