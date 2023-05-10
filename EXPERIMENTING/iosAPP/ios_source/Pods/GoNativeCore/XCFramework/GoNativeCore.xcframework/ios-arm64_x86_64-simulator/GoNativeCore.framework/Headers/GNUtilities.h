//
//  GNUtilities.h
//  GoNativeCore
//
//  Created by Hunaid Hassan on 18.10.21.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

NS_ASSUME_NONNULL_BEGIN

@interface GNUtilities : NSObject

+(NSDictionary*)parseQueryParamsWithUrl:(NSURL*)url;
+(UIColor *)colorFromHexString:(NSString *)hexString;

@end

NS_ASSUME_NONNULL_END
