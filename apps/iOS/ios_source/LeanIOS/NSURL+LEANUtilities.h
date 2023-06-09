//
//  NSURL+LEANUtilities.h
//  LeanIOS
//
//  Created by Weiyin He on 3/15/14.
// Copyright (c) 2014 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface NSURL (LEANUtilities)

- (BOOL)matchesPathOf:(NSURL*)url2;
- (BOOL)matchesIgnoreAnchor:(NSURL*)url2;
@end
